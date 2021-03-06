<?php

namespace PgAsync\Message;

class CommandComplete implements ParserInterface
{
    use ParserTrait;

    private $tag;
    private $oid = 0;
    private $rows = 0;

    /**
     * @inheritDoc
     */
    public function parseMessage($rawMessage)
    {
        $completeTag = substr($rawMessage, 5);
        $parts       = explode(" ", $completeTag);
        if (isset($parts[0])) {
            switch ($parts[0]) {
                case "INSERT":
                    $this->rows = $parts[1];
                    if ($parts[1] == 1 && $parts[2] != 0) {
                        $this->oid = $parts[2];
                    }
                    break;
                case "DELETE":
                case "UPDATE":
                case "SELECT":
                case "MOVE":
                case "FETCH":
                case "COPY":
                    $this->rows = $parts[1];
                    break;
            }

            $this->tag = $parts[0];
        }
    }

    /**
     * @inheritDoc
     */
    public static function getMessageIdentifier()
    {
        return 'C';
    }

    /**
     * @return mixed
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @return int
     */
    public function getOid()
    {
        return $this->oid;
    }

    /**
     * @return int
     */
    public function getRows()
    {
        return $this->rows;
    }
}
