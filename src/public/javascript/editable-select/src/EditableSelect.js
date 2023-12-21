import { EditableList } from "../../editable-list/src/EditableList"

export const EditableSelect = (params) => {

    const {
        id, width = 200,
        placeholder = "Select",
        dataSource = {},
        allowFilter,
        allowClear,
    } = params

    console.log(params)

    const floatingListId = `floatingList_${id}`
    const listParams = {
        id: floatingListId,
        width,
        dataSource,
        allowFilter,
        allowClear,
        float: true,
    }

    const floatingList = EditableList(listParams)
    const clearStr = `<button type="button" class="hover:bg-red-500 px-2 rounded-full flex items-center"><i class="fa-solid fa-delete-left"></i></button>`
    const arrowDown = `<div class="px-2 flex items-center"><i class="fa-solid fa-chevron-down"></i></div>`
    const dropdownId = `dropdown_${id}`

    const dropdown = `<div id="${dropdownId}" class="border rounded inline-block" tabindex="0" style="width:${width}px;">
        <div class="flex">
            <span class="m-1 w-full">${placeholder}</span>
            ${allowClear ? clearStr : ''}
            ${arrowDown}
        </div>
        </div>
        ${floatingList}
    `

    $(document).ready(() => {
        $(`#${dropdownId}`).click(() => {
            // console.log(`Clicked on ${dropdownId}`)

            const myFloatingList = $(`#${floatingListId}`)

            const isActive = myFloatingList.hasClass("active")
            if (!isActive) {
                myFloatingList.removeClass('opacity-0');
                myFloatingList.addClass('opacity-100');
                myFloatingList.addClass('active')

                let count = 2
                const handler = (event) => {
                    const myDiv = document.getElementById(floatingListId);
                    // console.log(count)
                    if (!myDiv.contains(event.target)) {
                        count--
                        // console.log(count)
                        if (count == 0) {
                            // console.log('Clicked outside the div');
                            // myFloatingList.addClass('hidden')
                            myFloatingList.addClass('opacity-0');
                            myFloatingList.removeClass('opacity-100');
                            myFloatingList.removeClass('active')
                            document.removeEventListener('click', handler)
                        }
                    }
                }
                document.addEventListener('click', handler)
            }
        })

    })

    return dropdown
}