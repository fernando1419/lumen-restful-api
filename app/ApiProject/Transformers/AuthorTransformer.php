<?php namespace App\ApiProject\Transformers;

class AuthorTransformer extends Transformer
{
    /**
     * transform
     *
     * @param mixed $author
     * @return void
     */
    public function transform($author)
    {
        // The apis will work on the keys rather than the name of the fields in authors DB table, that is why tranform is necessary.
        return [
            'name'         => $author['name'],
            'email'        => $author['email'],
            'github'       => $author['github'],
            'twitter'      => $author['twitter'],
            'location'     => $author['location'],
            'last_article' => $author['last_article_published'],
            'active'       => (boolean) $author['some_boolean']
        ];
    }

}
