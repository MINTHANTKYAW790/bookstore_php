<?php

const PI = "3.141528967";
interface Animal
{
    public function sound();
}
class Dog implements Animal
{
    public function sound()
    {
        echo "The dog is barking! <br>";
    }
}
class Cat implements Animal
{
    const NAME = "Mi War";
    public function sound()
    {

        echo  " The cat is meowoing! <br>";
    }
}

function app(Animal $obj)
{
    $obj->sound();
}
app(new Dog);
app(new Cat);
echo PI;
echo Cat::NAME;
