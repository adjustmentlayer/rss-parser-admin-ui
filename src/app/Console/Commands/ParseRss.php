<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;

class ParseRss extends Command
{
    protected $signature = 'lifehacker:parse-rss';
    protected $description = 'parse lifehacker rss';


    public function handle()
    {
        if (!($x = simplexml_load_file('https://lifehacker.com/rss','SimpleXMLElement', LIBXML_NOCDATA))) {
            return;
        };


        $items = $x->channel->item;
        $this->line("Start parsing rss feed");
        $bar = $this->output->createProgressBar(count($items));

        foreach ($items as $item)
        {
            if(Post::where('guid', $item->guid)->first()) {
                // Post already exists, check next post
                continue;
            }

            $bar->advance();
            $post = new Post();
            $post->title = $item->title;
            $post->link = $item->link;
            $post->guid = $item->guid;
            $post->description = $item->description;
            $post->pubDate = $item->pubDate;
            $post->save();

            $categories = $item->category;

            foreach($categories as $category) {
                Category::create([
                    'title' => $category,
                    'post_id' => $post->id
                ]);
            }
        }
    }
}
