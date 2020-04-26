require('./bootstrap');

import 'jquery-ui/ui/widgets/draggable.js';
import 'jquery-ui/ui/widgets/resizable.js';

$(function() {
    $( ".draggable" ).draggable();
    $( ".resizable" ).resizable();
});