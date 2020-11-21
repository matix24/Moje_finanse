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
            [3, 'asc']
        ],
        info: true,
        autoWidth: false,
        responsive: true,
        columnDefs: [
            { name: "u.id", targets: 0, orderable: true },
            { name: "u.name", targets: 1, orderable: true },
            { name: "u.surname", targets: 2, orderable: true },
            { name: "u.email", targets: 3, orderable: true },
            { name: "u.roles", targets: 4, orderable: true },
            { name: "u.isVerified", targets: 5, orderable: true },
            { name: "u.is_disabled", targets: 6, orderable: true },
            { name: "u.user_created_at", targets: 7, orderable: true },
            { name: "u.user_updated_at", targets: 8, orderable: true },
            { name: "buttons", targets: 9, orderable: false }
        ]
    }); //  end dataTable

    // tooltip dla wyszukiwarki
    appendTooltipToSearchDataTables('Wyszukuje po imieniu, nazwisku oraz adresie email.');

}); // end document ready

// WŁĄCZANIE I WYŁĄCZANIE UŻYTKOWNIKÓW
$(document).on('click', '.user-to-disable', function () {
    modalConfirmDelete($(this).attr('data-link'), 'Zmień status', 'Czy na pewno chcesz wyłączyć danego użytkownika?');
});
$(document).on('click', '.user-to-enable', function () {
    modalConfirm($(this).attr('data-link'), 'Zmień status', 'Czy na pewno chcesz włączyć danego użytkownika?');
});