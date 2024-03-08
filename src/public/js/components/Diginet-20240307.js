function closePopup() {
    document.getElementById('popup-container').classList.add('hidden');
    document.getElementById('popup-container').classList.add('hidden');
    $(document).off('click.postLink');
}

function handleEscapeKeyForPopup(event) {
    if (event.key === "Escape" || event.keyCode === 27) {
        closePopup();
        $(document).off('click.postLink');
    }
}

const getFirstAndLastDayOfMonth = (year, _month) => {
    // Calculate the year and month for the last day
    const yearFisrt = _month === 1 ? year - 1 : year;
    const monthFirst = _month === 1 ? 12 : String(_month - 1).padStart(2, '0');
    // Format the current month to two digits
    const month = String(_month).padStart(2, '0');
    // Define first and last days
    const firstDay = `${yearFisrt}-${monthFirst}-26`;
    const lastDay = `${year}-${month}-25`;
    return {
        firstDay: firstDay,
        lastDay: lastDay
    };
}


const openPopupShowEntities = (arrayRoutes, element) => {
    document.addEventListener('keydown', handleEscapeKeyForPopup);
    document.getElementById('top-title').innerHTML = element.getAttribute('top-title');
    document.getElementById('popup-container').classList.remove('hidden');

    let typeClick = element.getAttribute('type-click'); // get URL from data-url attribute
    var carName = element.getAttribute('data-name');
    var popupBody = $('#popup-container .body');

    popupBody.html(''); // delete old content
    popupBody.append('<div class="font-semibold text-xl pb-4"><strong></strong> ' + carName + '</div>');

    Object.entries(arrayRoutes).forEach(([key, value]) => {
        popupBody.append(`
            <a href="${typeClick === 'delete' ? '' : value}" data-url="${value}" data-entity="${key}" target="__blank" class=" mx-2 post-link inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-white bg-green-500 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">
                ${key}
                <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/></svg>
            </a>`
        );
    });
}

const deleteDatabase = (arrayRoutes, element) => {
    // Setup popup
    const popupContainer = document.getElementById('popup-container');
    popupContainer.classList.remove('hidden');
    document.getElementById('top-title').innerHTML = element.getAttribute('top-title');
    const carName = element.getAttribute('data-name');
    const popupBody = $('#popup-container .body');
    popupBody.html(`<div class="font-semibold text-xl pb-4"><strong></strong> ${carName}</div>`);

    // Populate popup with options
    Object.entries(arrayRoutes).forEach(([key, value]) => {
        popupBody.append(`
            <a href="${element.getAttribute('type-click') === 'delete' ? '' : value}" 
                data-url="${value}" 
                data-entity="${key}" 
                target="__blank" 
                class="mx-2 post-link inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-white bg-green-500 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">
                ${key}
                <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/></svg>
            </a>`
        );
    });

    // Add click event handler for post links
    $(document).on('click.postLink', '.post-link', handlePostLinkClick);
}

const handlePostLinkClick = function (e) {
    window.postLinkEventAdded = true;
    const isConfirmed = window.confirm("Are you sure you want to delete?");
    if (isConfirmed) {
        e.preventDefault();
        const url = $(this).data('url');
        const entity = $(this).data('entity');
        const processingToast = toastr.info('Processing your request...', {
            timeOut: 0,
            extendedTimeOut: 0,
            closeButton: true,
            progressBar: true,
            tapToDismiss: true
        });
        $.ajax({
            url,
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            contentType: 'application/json',
            data: JSON.stringify({ entity }),
            success: function (response, message) {
                if (response.results && response.results !== "undefined") {
                    const allResults = response.results;
                    allResults.forEach(item => {
                        toastr.clear(processingToast);
                        toastr.success(item.message);
                    });
                } else {
                    if (message === "success") {
                        toastr.clear(processingToast);
                        toastr.success(response);
                    } else {
                        toastr.warning(response);
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error('Error sending POST request', xhr, status, error);
                toastr.clear(processingToast);
                toastr.error('An error occurred: ' + xhr.responseJSON.message);
            }
        });
    } else {
        return false;
    }
};
