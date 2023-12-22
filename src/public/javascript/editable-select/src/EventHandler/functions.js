export const getEById = (id) => $("[id='" + id + "']")

export const DropdownOnBlur = (params) => {
    const { floatingListId, } = params
    document.removeEventListener('click', clickAwayHandlerArray[floatingListId])
    const myFloatingList = getEById(floatingListId)
    myFloatingList
        .addClass('invisible')
        .addClass('opacity-0')
        .removeClass('opacity-100')
        .removeClass('active')
}

const dropdownOnClickAndBlurCountArray = {}
const clickAwayHandlerArray = {}
const clickAwayHandler = (event, floatingListId) => {
    const myDiv = document.getElementById(floatingListId);
    // console.log(myDiv)
    // console.log(count)
    if (!myDiv.contains(event.target)) {
        dropdownOnClickAndBlurCountArray[floatingListId]--
        // console.log(dropdownOnClickAndBlurCountArray[floatingListId])
        if (dropdownOnClickAndBlurCountArray[floatingListId] === 0) {
            // console.log('Clicked outside the div', clickAwayHandlerArray[floatingListId]);
            DropdownOnBlur({ floatingListId })
        }
    }
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
        dropdownOnClickAndBlurCountArray[floatingListId] = 2

        //This will "instantiate" a new instance of a function
        clickAwayHandlerArray[floatingListId] = (e) => clickAwayHandler(e, floatingListId)
        document.addEventListener('click', clickAwayHandlerArray[floatingListId])
    }
}