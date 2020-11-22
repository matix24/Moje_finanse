/**************************************************
 * GŁÓWNY PLIK JS
 **************************************************/

/***** zmienna przechowująca styl ładowania datatables *****/
var ajaxDivLoader = "<div class='text-center'><img src='/img/helpers/ajax-loader.gif' /></div>";



/***************** PACE - SYSTEM LOADING ******************/
window.paceOptions = {
    ajax: {
        trackMethods: ['GET', 'POST', 'PUT', 'DELETE', 'REMOVE']
    }
};



/************************ MODALE *************************/

/**
 * Obsługa modala do zapytań czy zarchiwizować daną pozycję, danger => Archiwizuj
 * @param {string} link
 * @param {string} title
 * @param {string} content
 */
function modalConfirmArchive(link, title = '', content = '')
{
    $('.modal-submit-archive').attr('href', link);

    if (title != '') {
        $('.modal-confirm-archive-title').text(title);
    }
    if (content != '') {
        $('.modal-confirm-archive-body').html(content);
    }
    $('#modal-confirm-archive').modal();
} // end modalConfirmArchive

/**
 * Obsługa modala do zapytań czy usunąć daną pozycję, danger => Usuń
 * @param {string} link
 * @param {string} title
 * @param {string} content
 */
function modalConfirmDelete(link, title = '', content = '')
{
    $('.modal-submit-delete').attr('href', link);

    if (title != '') {
        $('.modal-confirm-delete-title').text(title);
    }
    if (content != '') {
        $('.modal-confirm-delete-body').html(content);
    }
    $('#modal-confirm-delete').modal();
} // end modalConfirmDelete

/**
 * Obsługa modala do zapytań czy potwierdzić, success => ok
 * @param {string} link
 * @param {string} title
 * @param {string} content
 */
function modalConfirm(link, title = '', content = '')
{
    $('.modal-submit-confirm').attr('href', link);

    if (title != '') {
        $('.modal-confirm-title').text(title);
    }
    if (content != '') {
        $('.modal-confirm-body').html(content);
    }
    $('#modal-confirm-confirm').modal();
} // end modalConfirm

/**
 * Obsługa modala XL
 * @param {string} linkFromLoad
 * @param {string} title
 */
function modalXL(linkFromLoad, title = '')
{
    if (title != '') {
        $('.modal-xl-title').text(title);
    }
    $('.modal-xl-body').html(ajaxDivLoader);
    $('#modal-xl').modal();

    $.getJSON(linkFromLoad, function (data) {
        if (typeof data.title !== 'undefined') {
            $('.modal-xl-title').text(data.title);
        }
        $('.modal-xl-body').html(data.html.content);
    });
} // end modalXLLoad



/************************ DATEPICKER *************************/
$.datepicker.regional['pl'] = {
    firstDay: 1,
    closeText: "Zamknij", // Display text for close link
    prevText: "Poprzedni", // Display text for previous month link
    nextText: "Następny", // Display text for next month link
    currentText: "Dzisiaj", // Display text for current month link
    monthNames: ["Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień"], // Names of months for drop-down and formatting
    monthNamesShort: ["Sty", "Lut", "Mar", "Kwi", "Maj", "Cze", "Lip", "Sie", "Wrz", "Paź", "Lis", "Gru"], // For formatting
    dayNames: ["Niedziela", "Poniedziałek", "Wtorek", "Środa", "Czwartek", "Piątek", "Sobota"], // For formatting
    dayNamesShort: ["Nd", "Pn", "Wt", "Śr", "Cz", "Pt", "Sb"], // For formatting
    dayNamesMin: ["Nd", "Pn", "Wt", "Śr", "Cz", "Pt", "Sb"], // Column headings for days starting at Sunday
    weekHeader: "Ty", // Column header for week of the year
    isRTL: false, // True if right-to-left language, false if left-to-right
    showMonthAfterYear: false, // True if the year select precedes month, false for month then year
    yearSuffix: "" // Additional text to append to the year in the month headers
};
$.timepicker.regional['pl'] = {
    firstDay: 1,
    timeOnlyTitle: 'Wybierz godzinę',
    closeText: "Zamknij",
    currentText: "Dzisiaj",
    timeText: 'Aktualnie',
    hourText: 'Godzina',
    minuteText: 'Minuta',
    secondText: 'Sekunda',
    hourMin: 7,
    hourMax: 17,
    hourGrid: 2,
    stepMinute: 10,
    minuteGrid: 10
};
$.datepicker.setDefaults($.datepicker.regional['pl']);
$.timepicker.setDefaults($.timepicker.regional['pl']);

$(".datetimepicker").datetimepicker({
    // ##### ZOSTAWIAM DLA PRZYKŁADU
    beforeShow: function (input, inst) {
        // $(".ui-datepicker").css('font-size', 12);
    },
    dateFormat: "yy-mm-dd",
    timeFormat: "HH:mm",
    locale: 'pl',
    showAnim: "fade",
    controlType: 'slider'
});
$(".datetimepicker-inversely").datetimepicker({
    dateFormat: "dd-mm-yy",
    timeFormat: "HH:mm",
    locale: 'pl',
    showAnim: "fade",
    controlType: 'slider'
});
$(".datepicker").datepicker({
    dateFormat: "yy-mm-dd",
    locale: 'pl',
    showAnim: "fade",
    controlType: 'slider'
});
$(".datepicker-inversely").datepicker({
    dateFormat: "dd-mm-yy",
    locale: 'pl',
    showAnim: "fade",
    controlType: 'slider'
});
$(".timepicker").timepicker({
    timeFormat: "HH:mm",
    locale: 'pl',
    showAnim: "fade",
    controlType: 'slider'
});



/************************ TOOLTIP *************************/
$(document).tooltip();



/********************** DATA TABLES ***********************/
/**
 * Dodaje specjalny tooltip do pola wyszukiwarki z
 * informacją po jakich polach wyszukuje wyszukiwarka.
 *
 * @param string message
 * @param string element
 * @return void
 */
function appendTooltipToSearchDataTables(message, element = '.dataTables_filter')
{
    let htmlButtonTooltip = ' <button class="btn btn-xs btn-secondary" data-toggle="tooltip" data-placement="top" title="' + message + '"><i class="far fa-question-circle"></i></button>';
    $(element).children().append(htmlButtonTooltip);
} // end appendTooltipToSearchDataTables


/************************ TOAST *************************/
/**
 * Tworzę komunikat SUCCESS
 * @param {string} message
 */
function toastSuccess(message = '')
{
    $(document).Toasts('create', {
        class: 'bg-success',
        title: 'Sukces!',
        body: message,
        icon: 'far fa-check-circle fa-lg'
    });
} // end toastSuccess
/**
 * Tworzę komunikat ERROR
 * @param {string} message
 */
function toastError(message = '')
{
    $(document).Toasts('create', {
        class: 'bg-danger',
        title: 'Błąd!',
        body: message,
        icon: 'fas fa-exclamation-circle fa-lg'
    });
} // end toastError
/**
 * Tworzę komunikat NOTICE
 * @param {string} message
 */
function toastNotice(message = '')
{
    $(document).Toasts('create', {
        class: 'bg-info',
        title: 'Informacja!',
        body: message,
        icon: 'fas fa-info-circle fa-lg'
    });
} // end toastNotice
/**
 * Tworzę komunikat WARNING
 * @param {string} message
 */
function toastWarning(message = '')
{
    $(document).Toasts('create', {
        class: 'bg-warning',
        title: 'Ostrzeżenie!',
        body: message,
        icon: 'fas fa-exclamation-triangle fa-lg'
    });
} // end toastWarning


/************************ HELPERY *************************/

/**
 * Zwracam dane z podanego ciągu
 * @param {string} link
 * @return {JSON}
 */
function getHTMLFromJSON(link)
{
    $.getJSON(link, function (data) {
        console.log(data);
        return data;
    });
} // end getHTMLFromJSON

/**
 * Sprawdzam czy dana zmienna jest typu json
 * @param mixed str
 * @return {boolean}
 */
function isJsonString(str)
{
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
} // end isJsonString