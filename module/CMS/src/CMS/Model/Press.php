<?php

namespace CMS\Model;

use CMS\Service\PageTypes;

class Press
{

    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function getMoreDetails($newsId)
    {
        // used findBy to produce array to use parpareNews
        $news = $this->query->findBy('CMS\Entity\Page', array(
            'id' => $newsId
                )
        );
        if ($news[0]->type == PageTypes::PRESS_RELEASE_TYPE) {
            return $this->prepareNews($news);
        }
        return null;
    }

    private function prepareNews($news)
    {

        foreach ($news as $singleNews) {
            $newsPicture = explode('/', $singleNews->picture['tmp_name']);
            $singleNews->picture['tmp_name'] = $newsPicture[7];
            $singleNews->created = $singleNews->created->format('d-m-Y');
            $singleNews->body = $singleNews->getBody();
            }
        return $news;
    }

}
