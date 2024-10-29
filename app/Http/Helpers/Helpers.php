<?php

use Carbon\Carbon;

function get_avatar_color() {
    $avatar_colors = config('app.avatar_color');
    shuffle($avatar_colors);
    return $avatar_colors[0];
}

function currency_code() {
    return 'AUD';
}

function currency_symbol(){
    $currency = currency_code();
    if($currency == 'USD') {
        return "$";
    }

    if($currency == 'AUD') {
        return "$";
    }

    return "$";
}

function price_format($price, $symbol = 'AUD'){
    if($price >= 0) {
        return currency_symbol($symbol) . number_format($price, 2);
    } else {
        return '-'. currency_symbol($symbol) . number_format(abs($price), 2);
    }
}

function discount_price($price, $discount, $type){
	if($type == 'fixed') {
        $discount_price = $price - $discount;
    }
    
    if($type == 'percent') {
        $discount_price = $price - (($price / 100) * $discount);
    }

    return $discount_price;
}

function remaining_date($date) {
    return Carbon::now()->diff(Carbon::parse($date))->format('%d Days');
}

function leadStatus($key = null) {
    $data = [
        "new" => "New",
        "pending" => "Pending",
        "assigned" => "Assigned",
        "in-progress" => "In Progress",
        "on-hold" => "On Hold",
        "follow-up" => "Follow Up",
        "duplicate" => "Duplicate",
        "contacted" => "Contacted",
        "qualified" => "Qualified",
        "unqualified" => "Unqualified",
        "lost" => "Lost",
        "closed" => "Closed"
    ];

    if(!$key) {
        return $data;
    }

    return isset($data[$key]) ? $data[$key] : null;
}

function formatText($str) {
    $formattedText = str_replace('-', ' ', $str);
    $formattedText = ucwords($formattedText);
    return $formattedText;
}