const getRenderReadOnly = (cell) => {
    // if (cell === null || cell === undefined || cell === '' || cell === 'undefined')
    //     return `<i class="text-4xl text-blue-400 fa-solid fa-square-dashed"></i>`
    const t = `<i class="text-4xl text-blue-400 fa-solid fa-square-check"></i>`
    const f = `<i class="text-4xl text-blue-400 fa-solid fa-square-phone-hangup"></i>`
    return ((cell * 1) ? `${t}` : `${f}`)
}

export const ETRToggle = (cell, column) => {
    return `<div class="text-center w-full">
        ${getRenderReadOnly(cell)}
    </div>`
}