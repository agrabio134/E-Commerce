<?php

class Product
{
  private $id;
  private $name;
  private $price;
  private $description;
  private $image;

  public function __construct($id, $name, $price, $description, $image)
  {
    $this->id = $id;
    $this->name = $name;
    $this->price = $price;
    $this->description = $description;
    $this->image = $image;
  }

  public function getID()
  {
    return $this->id;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getPrice()
  {
    return $this->price;
  }

  public function getDescription()
  {
    return $this->description;
  }

  public function getImage()
  {
    return $this->image;
  }

  public function setName($name)
  {
    $this->name = $name;
  }

  public function setPrice($price)
  {
    $this->price = $price;
  }

  public function setDescription($description)
  {
    $this->description = $description;
  }

  public function setImage($image)
  {
    $this->image = $image;
  }
}

?>

