<?php
/**
 * picoblog
 * 
 * made by hxii (https://0xff.nu/picoblog)
 * 
 * Picoblog is a very simple front-end for a twtxt (https://github.com/prologic/twtxt) format microblog with support for:
 * - Limited Markdown (strong, em, marked, deleted, links, images, inline code).
 * - Tags (#tags are automatically converted to links).
 * - Unique IDs (I use them, but they are optional).
 */

namespace hxii;

class PicoBlog
{

    private $sourcefile, $format;
    public $rawentries, $blog;

    /**
     * Constructor.
     *
     * @param string $sourcefile Source file in twtxt format (or PicoBlog format).
     */
     //twtxt
    public function __construct(string $sourcefile, string $format = 'picoblog')
    {
        $this->sourcefile = $sourcefile;
        $this->format = $format;
        $this->readSource();
    }

    /**
     * Check for and parse query string from $_SERVER['QUERY_STRING']).
     * Used to get entries by ID or tag.
     *
     * @return array|boolean
     */
    public function parseQuery()
    {
        if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], $return);
            return $return;
        } else {
          return false;
        }
    }

    /**
     * Read source file/URL
     *
     * @return boolean true if successful, false if not
     */
    private function readSource()
    {
        if (is_file($this->sourcefile) && is_readable($this->sourcefile)) {
            $this->rawentries = explode(PHP_EOL, file_get_contents($this->sourcefile));
            if (!empty($this->rawentries)) {
                return true;
            }
        } elseif ($url = filter_var($this->sourcefile, FILTER_VALIDATE_URL)) {
            $this->rawentries = preg_split('/\x0A/', file_get_contents($url));
            if (!empty($this->rawentries)) {
                return true;
            }
        }
        throw new \Exception("{$this->sourcefile} is empty! Aborting.");
        return false;
    }

    /**
     * Parse entries from source file and replace tags with links
     *
     * @param array $entries array of raw entries
     * @return void
     */
    private function parseEntries(array $entries, bool $parseTags = true)
    {
        switch ($this->format) {
            case 'twtxt':
                $pattern = '/^(?<date>[^\t]+)\t(?<entry>.+)/';
                break;
            case 'picoblog':
                $pattern = '/^(?<date>[^\t]+)\t\(#(?<id>[a-zA-Z0-9]{6,7})\)\t(?<entry>.+)/';
                break;
        }
        foreach ($entries as $i => $entry) {
            preg_match($pattern, $entry, $matches);
            if (!$matches) continue;
            $id = (!empty($matches['id'])) ? $matches['id'] : $i;
            $matches['entry'] = $this->parseUsers($matches['entry']);
            $matches['entry'] = $this->parseHashlinks($matches['entry']);
            $parsedEntries[$id] = [
                'date' => $matches['date'],
                'entry' => ($parseTags) ? preg_replace('/#(\w+)?/', '<a href="?tag=$1" class="tag">#${1}</a>', $matches['entry']) : $matches['entry'],
            ];
        }
        return $parsedEntries;
    }

    /**
     * Parse user's nick
     *
     * @param string $entry
     * @return string string with parsed users
     */
    /*private function parseNick(array $entries)
    {
        $pattern = '/^(?<date>[^\t]+)\t(?<nick>.+)/';
        preg_match('# nick\t=\t]+)\t(?<nick>.+)/', $nick, $matchn);
        //if (!$matches) continue;
        //$id = (!empty($matches['id'])) ? $matches['id'] : $i;
        //$matches['entry'] = $this->parseUsers($matches['entry']);
        //$matches['entry'] = $this->parseHashlinks($matches['entry']);
        $parsedNick = [
            'nick' => $matchn['nick']
        ];
        return $parsedNick;
    }
    private function parseNick(string $entry) {
        $pattern = "# nick        = (.+)";
        $aaa=preg_match_all($pattern, $this->rawentries, $matches);
        print_r($aaa);
        return print_r($aaa);
    }*/

    /**
     * Parse any mentioned users in twtxt format: @<username https://feed-url>
     *
     * @param string $entry
     * @return string string with parsed users
     */
    private function parseUsers(string $entry) {
        $pattern = '/\@<([a-zA-Z0-9\.]+)\W+(https?:\/\/[^>]+)>/';
        return preg_replace($pattern,'<a href="$2">@$1</a>',$entry);
    }

    /**
     * Parse any hashtags in twtxt.net hashlink format: #<tag https://feed-url>
     *
     * @param string $entry
     * @return string string with parsed hashtags
     */
    private function parseHashlinks(string $entry) {
        $pattern = '/#<(\w+)\W+(https?:\/\/[^>]+)>/';
        return preg_replace($pattern, '<a href="$2">#$1</a>', $entry);
    }

    /**
     * Returns a filtered list of raw entries
     *
     * @param string|array $search entry filter. can be 'all', 'newest', 'oldest', 'random' or an ID/Tag.
     * @param bool $reverse return array in reverse order
     * For ID, we're looking for ['id'=>'IDHERE']. For tag, we're looking for ['tag'=>'tagname']
     * @return boolean|array
     */
    public function getEntries($search, bool $reverse = true)
    {
        switch ($search) {
            case '':
                return false;
            case 'all':
                return ($reverse) ? array_reverse($this->rawentries) : $this->rawentries;
            case 'newest':
                return [reset($this->rawentries)];
            case 'oldest':
                return [end($this->rawentries)];
            case 'random':
                return [$this->rawentries[array_rand($this->rawentries, 1)]];
            default:
                if (isset($search['id'])) {
                    $filter =  array_filter($this->rawentries, function ($entry) use ($search) {
                        preg_match("/\b$search[id]\b/i", $entry, $match);
                        return $match;
                    });
                    return $filter;
                } elseif (isset($search['tag'])) {
                    $filter =  array_filter($this->rawentries, function ($entry) use ($search) {
                        preg_match("/#\b$search[tag]\b/i", $entry, $match);
                        return $match;
                    });
                    return $filter;
                }
                return false;
        }
    }
    /**
     * Render Markdown in given entries and output as HTML
     *
     * @param array $entries array of parsed entries to render
     * @param string $entryWrap tne entry wrapper, e.g. <li>{entry}</li>
     * @param bool $parseTags should #tags be parsed to links?
     * @return string entries in HTML
     */
    public function renderEntries(array $entries, $show, string $entryWrap = '<li>{entry}</li>', bool $parseTags = false)
    {
        if (!$entries) return false;
        $entries = $this->parseEntries($entries, $parseTags);
        //$nick = $this->parseNick($nick);
        require_once('includes/Slimdown.php');
        $html = '';
        foreach ($entries as $id => $entry) {
            include "settings.php";
            $text = \Slimdown::render($entry['entry']);
            $date = $entry['date'];
            $date_p = date("M j, Y", strtotime($entry['date']));
            if($show!=true){
              $text = "<a href='?id={$id}'><div class='e index'><p><b><img class='pp' src='{$avatar}'> {$nick} • </b><span class='date' title='{$date}'>{$date_p}</span></p><span class='text'>" . $text . "</span></div></a>";
            } else {
              $text = "<div class='e show'><h3><a href='{$url}'><img class='pp' src='{$avatar}'> {$nick}</a> • <span class='date' title='{$date}'>{$date_p}</span><span class='date' style='float:right'>#{$id}<span></h3><h2 class='text'>" . $text . "</h2></div>";
            }
            $html .= str_replace('{entry}', $text, $entryWrap);
        }
        return $html;
    }
}
