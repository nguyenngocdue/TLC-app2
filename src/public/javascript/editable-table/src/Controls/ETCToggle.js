const getRender = (currentValue, controlId) => {
    const value = !!(currentValue * 1)
    const checked = value ? "checked" : ""
    const className = `w-6 h-6 rounded`
    return `<input type="checkbox" id="${controlId}" class="${className} w-" value="${value}" ${checked} >`
}

export const ETCToggle = (params) => {
    // console.log(params)
    const { currentValue, controlId } = params
    return `${getRender(currentValue, controlId)}`
}