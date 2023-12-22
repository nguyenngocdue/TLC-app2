const getRender = (currentValue, controlId) => {
    const value = !!(currentValue * 1)
    const checked = value ? "checked" : ""
    return `<input type="checkbox" id="${controlId}" class="w-8 h-8" value="${value}" ${checked} >`
}

export const ETCToggle = (params) => {
    // console.log(params)
    const { currentValue, controlId } = params
    return `<div class="text-center w-full">
       ${getRender(currentValue, controlId)}
    </div>`
}