<?php

function medium_date($date_str)
{
    return $date_str->timeZone('Africa/Nairobi')->format('jS M, Y | g:i A T');
}

function time_diff($date_str)
{
    return $date_str->timeZone('Africa/Nairobi')->diffForHumans();
}
