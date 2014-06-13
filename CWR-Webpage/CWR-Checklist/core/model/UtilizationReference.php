<?php

/**
 *
 * @author Alex Gabriel Castañeda
 */
class UtilizationReference {

    private $id;
    private $name;
    private $publication;
    private $page;
    private $author;
    private $year;
    private $description;
    private $path;

    function __construct($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getPublication() {
        return $this->publication;
    }

    public function getPage() {
        return $this->page;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function getYear() {
        return $this->year;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPath() {
        return $this->path;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setPublication($publication) {
        $this->publication = $publication;
    }

    public function setPage($page) {
        $this->page = $page;
    }

    public function setAuthor($author) {
        $this->author = $author;
    }

    public function setYear($year) {
        $this->year = $year;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setPath($path) {
        $this->path = $path;
    }

}

?>
