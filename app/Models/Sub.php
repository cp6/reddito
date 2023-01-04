<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Sub extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'name', 'posts', 'posts_18_plus', 'total_score', 'comments_on_posts', 'subscribers'];

    public static function processSubPosts(string $data): array
    {
        $start_timer = microtime(true);
        $date_f = date('Y-m-d H:i:s');
        $inserted = $updated = 0;

        try {
            $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $exception) {
            return ['success' => false, 'datetime' => $date_f, 'message' => 'Error decoding the JSON'];
        }

        $posts_array = array();

        if (isset($data['data']['children'][0])) {
            foreach ($data['data']['children'] as $val) {

                $post_id = $val['data']['id'];
                $posted_at = gmdate("Y-m-d H:i:s", $val['data']['created_utc']);
                $upvotes = $val['data']['ups'];
                $comments = $val['data']['num_comments'];
                $sub_id = $val['data']['subreddit_id'];
                $domain_id = Domain::idForDomain($val['data']['domain']);

                try {
                    self::updateOrCreate(['id' => $sub_id], ['name' => $val['data']['subreddit'], 'subscribers' => $val['data']['subreddit_subscribers']]);
                } catch (\Exception $exception) {
                    Log::debug("Sub::updateOrCreate exception $sub_id ({$val['data']['subreddit']})");
                }

                $awards_count = count($val['data']['all_awardings']);

                if ($val['data']['author'] !== '[deleted]') {
                    $author_id = $val['data']['author_fullname'];
                    Author::updateOrCreate(['id' => $author_id], ['username' => $val['data']['author']]);

                    $post_insert_update = Post::updateOrCreate([
                        'id' => $post_id
                    ], [
                        'author_id' => $author_id,
                        'sub_id' => $sub_id,
                        'domain_id' => $domain_id,
                        'status' => 1,
                        'is_self' => (int)$val['data']['is_self'],
                        'over_18' => (int)$val['data']['over_18'],
                        'locked' => (int)$val['data']['locked'],
                        'has_awards' => (isset($val['data']['all_awardings'][0])),
                        'score' => $upvotes,
                        'comments' => $comments,
                        'awards' => $awards_count,
                        'cross_posts' => $val['data']['num_crossposts'],
                        'upvote_ratio' => $val['data']['upvote_ratio'],
                        'created_at' => $posted_at,
                    ]);

                    PostProcessQueue::updateOrCreate(['id' => $post_id]);

                    $original_title = utf8_encode(trim(substr($val['data']['title'], 0, 255)));
                    $cleaned_the_title = preg_replace('/\s+/', ' ', preg_replace('/[^A-Za-z0-9\-()$_|!*@"%?=+.,\':\[\]\/]/', ' ', $original_title));

                    if ($original_title !== $cleaned_the_title) {
                        $title_was_cleaned = 1;
                        $cleaned_title = $cleaned_the_title;
                    } else {
                        $title_was_cleaned = 0;
                        $cleaned_title = null;
                    }

                    Title::updateOrCreate(['id' => $post_id], [
                        'title' => $original_title,
                        'cleaned_title' => $cleaned_title,
                        'was_cleaned' => $title_was_cleaned
                    ]);

                    Url::updateOrCreate([
                        'id' => $post_id
                    ], [
                        'domain_id' => $domain_id,
                        'main' => substr($val['data']['url'], 0, 255),
                        'dest' => (isset($val['data']['url_overridden_by_dest'])) ? substr($val['data']['url_overridden_by_dest'], 0, 255) : null,
                        'thumbnail' => (filter_var($val['data']['thumbnail'], FILTER_VALIDATE_URL)) ? $val['data']['thumbnail'] : null,
                        'other' => $val['data']['secure_media']['reddit_video']['fallback_url'] ?? null,
                        'bitrate' => $val['data']['secure_media']['reddit_video']['bitrate_kbps'] ?? null,
                        'height' => $val['data']['secure_media']['reddit_video']['height'] ?? null,
                        'width' => $val['data']['secure_media']['reddit_video']['width'] ?? null,
                        'duration' => $val['data']['secure_media']['reddit_video']['duration'] ?? null,
                    ]);

                    if ($val['data']['is_self']) {
                        SelfText::updateOrCreate(['id' => $post_id], [
                            'text' => $val['data']['selftext'] ?? null,
                            'html' => $val['data']['selftext_html'] ?? null
                        ]);
                    }

                    if (isset($val['data']['author_flair_template_id'])) {//Author has a flair
                        Flair::do($val);
                        FlairsForPost::do($post_id, $author_id, $val);
                    }

                } else {//Deleted

                    $post_insert_update = Post::where('id', $post_id)->take(1)->update([
                        'sub_id' => $sub_id,
                        'domain_id' => $domain_id,
                        'status' => 0,
                        'over_18' => (int)$val['data']['over_18'],
                        'locked' => (int)$val['data']['locked'],
                        'has_awards' => ($awards_count > 0) ? 1 : 0,
                        'score' => $upvotes,
                        'comments' => $comments,
                        'awards' => $awards_count,
                        'cross_posts' => $val['data']['num_crossposts'],
                        'upvote_ratio' => $val['data']['upvote_ratio'],
                    ]);

                }

                if (empty($post_insert_update->changes)) {//New post
                    $inserted++;
                } else {//Existing post (update)
                    $updated++;

                    PostTracking::create([
                        'post_id' => $post_id,
                        'score' => $upvotes,
                        'comments' => $comments,
                        'awards' => $awards_count,
                        'has_awards' => ($awards_count > 0) ? 1 : 0,
                        'upvote_ratio' => $val['data']['upvote_ratio'],
                        'cross_posts' => $val['data']['num_crossposts'],
                        'locked' => (int)$val['data']['locked'],
                        'minutes_since_posted' => (int)round(abs(strtotime($date_f) - strtotime($posted_at)) / 60, 1),
                        'created_at' => $date_f
                    ]);

                }

                if (isset($val['all_awardings'][0])) {
                    foreach ($val['all_awardings'] as $award) {
                        Award::do($award);
                        AwardsForPost::do($post_id, $award);
                    }
                }

                if (isset($val['link_flair_template_id'])) {//Has a link/post flair
                    LinkFlair::do($val);
                    LinkFlairForPost::do($post_id, $val);
                }

                $posts_array[] = array(
                    'id' => $post_id, 'score' => $upvotes, 'comments' => $comments, 'awards' => $awards_count, 'sub' => $val['data']['subreddit'], 'title' => $original_title, 'domain' => $val['data']['domain'], 'user' => $val['data']['author']
                );

            }

        }

        $post_count = count($data['data']['children']);

        Fetch::create(['results' => $post_count, 'inserted' => $inserted, 'updated' => $updated]);

        return [
            'success' => true, 'datetime' => $date_f, 'post_count' => $post_count, 'inserted' => $inserted, 'updated' => $updated,
            'seconds' => (float)number_format(microtime(true) - $start_timer, 2), 'posts' => $posts_array
        ];

    }


}
