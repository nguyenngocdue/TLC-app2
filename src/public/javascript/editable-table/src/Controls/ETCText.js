export const ETCText = (controlParams) => {
    const { currentValue, column, controlId } = controlParams
    const className = `w-full p-0`
    return `<input id="${controlId}" type="text" class="${className}" value="${currentValue}">`
}