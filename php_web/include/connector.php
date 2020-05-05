<?php
/**
 * Created by PhpStorm.
 * User: azizt
 * Date: 2/20/2017
 * Time: 3:58 PM
 */

function connectAccordingly()
{
    return new mysqli("localhost","root","","ctf");
    //return new mysqli("localhost","ctf","ctf","ctf");
}