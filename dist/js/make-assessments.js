import { getParameterByName } from '/dist/js/function.js';

jQuery(function() {
    // Changing href redirects to contain GET variable
    const manageLink = document.getElementById('manageLink');
    manageLink.setAttribute('href', manageLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));
    const holdLink = document.getElementById('holdLink');
    holdLink.setAttribute('href', holdLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));
    const statisticsLink = document.getElementById('statisticsLink');
    statisticsLink.setAttribute('href', statisticsLink.getAttribute('href') + "?secHandle_ID=" + getParameterByName('secHandle_ID'));
});