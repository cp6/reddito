<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'author_id', 'sub_id', 'domain_id', 'status', 'is_self', 'over_18', 'locked', 'has_awards', 'score', 'comments', 'awards', 'cross_posts', 'upvote_ratio', 'created_at', 'updated_at'];

    public function processQueue(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PostProcessQueue::class, 'id', 'id');
    }

    public function sub(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Sub::class, 'id', 'sub_id');
    }

    public function author(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Author::class, 'id', 'author_id');
    }

    public function domain(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Domain::class, 'id', 'domain_id');
    }

    public function title(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Title::class, 'id', 'id');
    }

    public function url(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Url::class, 'id', 'id');
    }

    public static function processPost(Post $post): array
    {
        $date_f = date('Y-m-d H:i:s');

        try {
            $fetch_data = new Fetch("https://www.reddit.com/r/{$post->sub->name}/comments/{$post->id}.json");
            $data = json_decode($fetch_data->getData(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $exception) {
            return ['success' => false, 'datetime' => $date_f, 'message' => 'Error decoding the JSON'];
        }

        if (isset($data[0]['data']['children'][0]['data'])) {
            $val = $data[0]['data']['children'][0]['data'];

            $upvotes = $val['ups'];
            $upvote_ratio = $val['upvote_ratio'];
            $comments = $val['num_comments'];
            $awards = $val['total_awards_received'];
            $locked = (int)$val['locked'];
            $cross_posts = $val['num_crossposts'];
            $over_18 = (int)$val['over_18'];

            ($val['author'] !== '[deleted]') ? $status = 1 : $status = 0;

            $post->update([
                'status' => $status,
                'score' => $upvotes,
                'comments' => $comments,
                'upvote_ratio' => $upvote_ratio,
                'awards' => $awards,
                'locked' => $locked,
                'cross_posts' => $cross_posts,
                'over_18' => $over_18
            ]);


            if (isset($val['all_awardings'][0])) {
                foreach ($val['all_awardings'] as $award) {
                    Award::do($award);
                    AwardsForPost::do($post->id, $award);
                }
            }

            if (isset($val['link_flair_template_id'])) {//Has a link/post flair
                LinkFlair::do($val);
                LinkFlairForPost::do($post->id, $val);
            }

        }

    }

    public static function updatePostsFromSub(Sub $sub, int $amount = 99)
    {
        $date_f = date('Y-m-d H:i:s');

        try {
            $fetch_data = new Fetch("https://www.reddit.com/r/{$sub->name}/new.json?sort=new&limit={$amount}");
            $data = json_decode($fetch_data->getData(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $exception) {
            return ['success' => false, 'datetime' => $date_f, 'message' => 'Error decoding the JSON'];
        }

        if (isset($data['data']['children'][0])) {
            foreach ($data['data']['children'] as $val) {
                $post_id = $val['data']['id'];
                $posted_at = gmdate("Y-m-d H:i:s", $val['data']['created_utc']);
                $upvotes = $val['data']['ups'];
                $comments = $val['data']['num_comments'];
                $awards_count = count($val['data']['all_awardings']);

                if (isset($val['data']['author_fullname'])) {

                    $author_id = $val['data']['author_fullname'];

                    Author::updateOrCreate(['id' => $author_id], ['username' => $val['data']['author']]);

                    $sub_id = $val['data']['subreddit_id'];

                    $domain_id = Domain::idForDomain($val['data']['domain']);

                    $post_insert_update = self::updateOrCreate([
                        'id' => $post_id
                    ], [
                        'author_id' => $author_id,
                        'sub_id' => $sub_id,
                        'domain_id' => $domain_id,
                        'status' => 1,
                        'is_self' => (int)$val['data']['is_self'],
                        'over_18' => (int)$val['data']['over_18'],
                        'locked' => (int)$val['data']['locked'],
                        'has_awards' => ($awards_count > 0) ? 1 : 0,
                        'score' => $upvotes,
                        'comments' => $comments,
                        'awards' => $awards_count,
                        'cross_posts' => $val['data']['num_crossposts'],
                        'upvote_ratio' => $val['data']['upvote_ratio'],
                        'created_at' => $posted_at,
                        'updated_at' => null
                    ]);

                    PostProcessQueue::updateOrCreate(['id' => $post_id]);

                    if (empty($post_insert_update->changes)) {//New post
                        //$inserted++;
                    } else {//Existing post (update)
                        //$updated++;
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

                    if (isset($val['data']['link_flair_template_id'])) {//Has a link/post flair

                        LinkFlair::updateOrCreate(['id' => $val['data']['link_flair_template_id']], [
                            'type' => $val['data']['link_flair_type'], 'text' => $val['data']['link_flair_text'],
                            'text_color' => (!empty($val['data']['link_flair_text_color'])) ? $val['data']['link_flair_text_color'] : null,
                            'css_class' => (!empty($val['data']['link_flair_css_class'])) ? $val['data']['link_flair_css_class'] : null,
                            'richtext_1' => $val['data']['link_flair_richtext']['e'] ?? null, 'richtext_2' => $val['data']['link_flair_richtext']['t'] ?? null
                        ]);

                        LinkFlairForPost::updateOrCreate(['flair_id' => $val['data']['link_flair_template_id'], 'post_id' => $post_id], []);
                    }

                    if (isset($val['data']['author_flair_template_id'])) {//Author has a flair

                        Flair::updateOrCreate(['id' => $val['data']['author_flair_template_id']], [
                            'type' => $val['data']['author_flair_type'], 'text' => $val['data']['author_flair_text'],
                            'text_color' => (!empty($val['data']['author_flair_text_color'])) ? $val['data']['author_flair_text_color'] : null,
                            'background_color' => $val['data']['author_flair_background_color'],
                            'css_class' => (!empty($val['data']['author_flair_css_class'])) ? $val['data']['author_flair_css_class'] : null,
                            'richtext_1' => $val['data']['author_flair_richtext'][0]['a'] ?? null, 'richtext_2' => $val['data']['author_flair_richtext'][0]['u'] ?? null,
                            'richtext_3' => $val['data']['author_flair_richtext'][1]['e'] ?? null, 'richtext_4' => $val['data']['author_flair_richtext'][1]['t'] ?? null
                        ]);

                        FlairsForPost::updateOrCreate(['flair_id' => $val['data']['author_flair_template_id'], 'post_id' => $post_id], ['author_id' => $author_id]);
                    }

                } else {//Deleted
                    $post_insert_update = self::where('id', $post_id)->take(1)->update([
                        'status' => 0,
                        'is_self' => (int)$val['data']['is_self'],
                        'over_18' => (int)$val['data']['over_18'],
                        'locked' => (int)$val['data']['locked'],
                        'has_awards' => ($awards_count > 0) ? 1 : 0,
                        'score' => $upvotes,
                        'comments' => $comments,
                        'awards' => $awards_count,
                        'cross_posts' => $val['data']['num_crossposts'],
                        'upvote_ratio' => $val['data']['upvote_ratio'],
                        'created_at' => $posted_at,
                        'updated_at' => null
                    ]);
                }

                if ($awards_count > 0) {
                    foreach ($val['data']['all_awardings'] as $award) {
                        Award::updateOrCreate(['id' => $award['id']], [
                            'title' => $award['name'],
                            'desc' => $award['description'],
                            'price' => $award['coin_price'],
                            'icon' => $award['icon_url'],
                            'icon_small' => $award['resized_icons'][3]['url'] ?? null
                        ]);

                        AwardsForPost::updateOrCreate(['post_id' => $post_id, 'award_id' => $award['id']], [
                            'count' => $award['count']
                        ]);

                    }
                }
            }

        }

        return true;
    }

}
