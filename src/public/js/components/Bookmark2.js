function modifyGlobalVariable(sub_package, entity) {
    const items = allApps[sub_package]['items']
    for (let i = 0; i < items.length; i++) {
        if (items[i].name === entity) {
            items[i].bookmarked = !items[i].bookmarked
            return items[i].bookmarked
        }
    }
}

function modifySearchModalBookmarkButton(entity, newValue) {
    const bookmarkBtnSpan = document.getElementById(
        `searchModalBookmarkBtnSpan_${entity}`
    )
    const bookmarkBtnIcon = document.getElementById(
        `searchModalBookmarkBtnIcon_${entity}`
    )
    if (newValue) {
        bookmarkBtnSpan.classList.remove('hover:bg-blue-700')
        bookmarkBtnSpan.classList.add('hover:bg-pink-700')
        bookmarkBtnIcon.classList.remove('text-gray-400')
        bookmarkBtnIcon.classList.add('text-blue-600')
    } else {
        bookmarkBtnSpan.classList.remove('hover:bg-pink-700')
        bookmarkBtnSpan.classList.add('hover:bg-blue-700')
        bookmarkBtnIcon.classList.remove('text-blue-600')
        bookmarkBtnIcon.classList.add('text-gray-400')
    }
}

function modifyTopDrawerBookmarkButton(entity, newValue) {
    var bookmarkBtnSpans = $(`[data-id="topDrawerBookmarkBtnSpan_${entity}"]`)
    var bookmarkBtnIcons = $(`[data-id="topDrawerBookmarkBtnIcon_${entity}"]`)
    if (newValue) {
        bookmarkBtnSpans
            .removeClass('hover:bg-blue-700')
            .addClass('hover:bg-pink-700')
        bookmarkBtnIcons.removeClass('text-gray-400').addClass('text-blue-600')
    } else {
        bookmarkBtnSpans
            .removeClass('hover:bg-pink-700')
            .addClass('hover:bg-blue-700')
        bookmarkBtnIcons.removeClass('text-blue-600').addClass('text-gray-400')
    }
}

function toggleBookmark(entity, sub_package, url, source) {
    console.log('Toggle bookmark', sub_package, entity)
    $.ajax({
        type: 'put',
        url,
        data: { entity },
        success: function (response) {
            if (response.success) {
                // toastr.success(response.message, 'Bookmark')
                const newValue = modifyGlobalVariable(sub_package, entity)
                BookmarkBarInit()
                switch (source) {
                    case 'searchModal':
                        modifySearchModalBookmarkButton(entity, newValue)
                        break
                    case 'topDrawer':
                        modifyTopDrawerBookmarkButton(entity, newValue)
                        break
                }
            }
        },
        error: function (response) {
            console.error(response)
        },
    })
}
