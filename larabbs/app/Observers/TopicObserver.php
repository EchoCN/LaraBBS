<?php

namespace App\Observers;

use App\Models\Topic;
use App\Jobs\TranslateSlug;
use App\Handlers\SlugTranslateHandler;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
	public function saving(Topic $topic)
	{
		$topic->body = clean($topic->body,'user_topic_body');
		
		$topic->excerpt = make_excerpt($topic->body);

		if( ! $topic->slug)
		{
			dispatch(new TranslateSlug($topic));
		}
	}

	public function delete(Topic $topic)
	{
		\DB::table('replies')->where('topic_id',$topic->id)->delete();
	}
}