<?php

function getTextTemplate($str, $info) {
    switch ($str) {
        case 'facebook':
            return 'Facebook';
        case 'zalo':
            return 'Zalo';
        case 'instagram':
            return 'Instagram';
        case 'youtube':
            return 'Youtube';
        case 'skype':
            return 'Skype';
        case 'tiktok':
            return 'Tiktok';
        case 'momo':
            return 'Momo';
        case 'shopee':
            return 'Shopee';
        case 'twitter':
            return 'Twitter';
        case 'website':
            return 'Website';
        case 'telegram':
            return 'Telegram';
        case 'lazada':
            return 'Lazada';
        case 'wechat':
            return 'Wechat';
        case 'whatsapp':
            return 'Whatsapp';
        case 'linkedin':
            return 'Linkedin';
        case 'soundcloud':
            return 'Soundcloud';
        case 'spotify':
            return 'Spotify';
        default :
            return $info;
    }
}


function getTextTemplate2($str) {
    switch ($str) {
        case 'name':
            return 'Họ tên';
        case 'birthday':
            return 'Năm sinh';
        case 'job':
            return 'Công việc';
        case 'address':
            return 'Địa chỉ';
        case 'hometown':
            return 'Sống tại';
        case 'education':
            return 'Học vấn';
        default :
            return '';
    }
}
