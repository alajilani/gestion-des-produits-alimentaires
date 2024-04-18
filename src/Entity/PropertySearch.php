<?php

namespace App\Entity;

class PropertySearch
{

   private $id;

   
   public function getNom(): ?string
   {
       return $this->id;
   }

   public function setNom(string $id): self
   {
       $this->id = $id;

       return $this;
   }
}