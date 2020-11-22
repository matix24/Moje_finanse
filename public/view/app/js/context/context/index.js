$(document).ready(function () {
    $('.dataTable').DataTable({
        ajax: {
            url: userListUrl,
            type: "POST",
            beforeSend: function (xhr) {},
            complete: function (json) {}
        },
        language: {
            processing: ajaxDivLoader,
            zeroRecords: "Brak wyników, wybierz inne kryteria wyszukiwania lub wyczyść filtrowanie",
            info: "Strona _PAGE_ z _PAGES_",
            infoEmpty: "",
            search: "Szukaj:",
            lengthMenu: "Pozycji _MENU_",
            paginate: {
                first: "Pierwsza",
                last: "Ostatnia",
                next: "Następna",
                previous: "Poprzednia"
            }
        },
        ordering: true,
        processing: true,
        serverSide: true,
        paging: true,
        searching: true,
        lengthChange: true,
        lengthMenu: [
            [50, 100, 500, 1000, 5000, 10000, -1],
            ["50", "100", "500", "1 000", "5 000", "10 000", "All"]
        ],
        iDisplayStart: 0,
        order: [
            [0, 'asc']
        ],
        info: true,
        autoWidth: false,
        responsive: true,
        colReorder: true,
        columnDefs: [
            { targets: 0, name: "c.name", orderable: true, responsivePriority: 1 },
            { targets: 1, name: "c.description", orderable: true, responsivePriority: 2 },
            { targets: 2, name: "c.is_archive", orderable: true },
            { targets: 3, name: "c.created_at", orderable: true },
            { targets: 4, name: "c.updated_at",  orderable: true },
            { targets: 5, name: "buttons", orderable: false, responsivePriority: 3 }
        ]
    }); //  end dataTable

    // tooltip dla wyszukiwarki
    appendTooltipToSearchDataTables('Wyszukuje po nazwie.');

}); // end document ready

// WŁĄCZANIE I WYŁĄCZANIE KONTEKSTÓW
$(document).on('click', '.context-to-delete', function () {
    modalConfirmDelete($(this).attr('data-link'), 'Usuń', 'Czy na pewno chcesz usunąć dany kontekst?');
});
$(document).on('click', '.context-to-disable', function () {
    modalConfirmArchive($(this).attr('data-link'), 'Zmień status', 'Czy na pewno chcesz wyłączyć dany kontekst?');
});
$(document).on('click', '.context-to-enable', function () {
    modalConfirm($(this).attr('data-link'), 'Zmień status', 'Czy na pewno chcesz włączyć dany kontekst?');
});