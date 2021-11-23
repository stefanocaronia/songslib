import $ from 'jquery';
require('@fortawesome/fontawesome-free/js/all.js');
require('bootstrap');

import '../styles/app.scss';

$('.flashbag-messages').children().each(function(){
    $(this).delay(3000).fadeOut(1000);
});