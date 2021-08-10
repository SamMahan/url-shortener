<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class UrlHash extends Model 
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hash_key', 'url',
    ];
    
    
    /**
     * SCOPES
     */

    /**
     * Scope a query to only include users of a given type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $key
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasHashKey(Builder $query, string $key): Builder {
        return $query->where('hash_key', $key);
    }

    public function scopeSortedByViews(Builder $query):Builder {
        return $query->orderBy('times_accessed', 'desc');
    }

    /**
     * Utilities
     * general helpful functions
     */

     public function incrementTimesAccessed():void {
         $this->times_accessed++;
     }

     public function saveHashKey():void {
         $this->hash_key = $this->idToShortUrl();
     }
     /**
      * this function was adapted from an example seen here:
      * https://www.geeksforgeeks.org/how-to-design-a-tiny-url-or-url-shortener/
      * This function generates a unique URL-friendly string based on the entity's 
      * numeric 'id' field. This is better than hashing the url as there is no risk of collision
      * with two URL's resolving to the same hash string
      * @return string $shortUrl
      */
    public function idToShortURL():string {
        if (!$this->id) {
            throw new Exception('This object does not have an Id, it must be saved first');
        }
        $n = $this->id;
        // Map to store 62 possible characters
        $map = "abcdefghijklmnopqrstuvwxyzABCDEF
                GHIJKLMNOPQRSTUVWXYZ0123456789";
        $map = str_split($map); //convert the string into an array of characters
        $shortUrl = '';
    
        // Convert given integer id to a base 62 number
        while ($n)
        {
            // use above map to store actual character
            // in short url
            $shortUrl = $shortUrl . $map[$n%62];
            $n = floor($n/62);
        }
    
        // Reverse shortURL to complete base conversion
        $shortUrl = strrev($shortUrl);
    
        return $shortUrl;
    }

    /**
     * Overrides
     * 
     */

    /**
     * Since our 'key' comes from an auto-incremented database ID
     * We need to save once to acquire the ID and then again when we save our hash key
     * We do this in a transaction to make sure we don't have a race-condition
     * between inserting the initial object and a query
     * @param $options the general laravel save options
     */
    public function save(array $options = []): void {
        DB::transaction(function() use ($options) {
            parent::save($options);
            if (!$this->hash_key) {
                $this->saveHashKey();
                $this->save();
            }
        });
        
    }
}