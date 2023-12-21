export const getEById = (id) => $("[id='" + id + "']")

const clickAwayHandler = (event, floatingListId, count) => {
    const myDiv = document.getElementById(floatingListId);
    // console.log(myDiv)
    // console.log(count)
    if (!myDiv.contains(event.target)) {
        count--
        // console.log(count)
        if (count == 0) {
            // console.log('Clicked outside the div');
            document.removeEventListener('click', clickAwayHandler)
            myFloatingList
                .addClass('invisible')
                .addClass('opacity-0')
                .removeClass('opacity-100')
                .removeClass('active')
        }
    }
}

export const DropdownOnBlur = (params) => {
    const { floatingListId, } = params
    const myFloatingList = getEById(floatingListId)
    document.removeEventListener('click', clickAwayHandler)
    myFloatingList
        .addClass('invisible')
        .addClass('opacity-0')
        .removeClass('opacity-100')
        .removeClass('active')
}

export const DropdownOnClickAndBlur = (params) => {
    // console.log(`Clicked on ${dropdownId}`)
    const { floatingListId, } = params

    const myFloatingList = getEById(floatingListId)
    // const myFloatingList = $(`#${floatingListId}`)

    const isActive = myFloatingList.hasClass("active")
    if (!isActive) {
        myFloatingList
            .removeClass('invisible')
            .removeClass('opacity-0')
            .addClass('opacity-100')
            .addClass('active')

        let count = 2
        // const clickAwayHandler = (event) => {
        //     const myDiv = document.getElementById(floatingListId);
        //     // console.log(count)
        //     if (!myDiv.contains(event.target)) {
        //         count--
        //         // console.log(count)
        //         if (count == 0) {
        //             // console.log('Clicked outside the div');
        //             document.removeEventListener('click', clickAwayHandler)
        //             myFloatingList
        //                 .addClass('invisible')
        //                 .addClass('opacity-0')
        //                 .removeClass('opacity-100')
        //                 .removeClass('active')
        //         }
        //     }
        // }
        document.addEventListener('click', (e) => clickAwayHandler(e, floatingListId, count))
    }
}