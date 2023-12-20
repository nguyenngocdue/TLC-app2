const icons = {
    move: `fa-solid fa-grip-vertical `,
    toggle: `fa-solid fa-square-check `,
    clone: `fa-solid fa-copy `,
    trash: `fa-solid fa-trash `,
}

const btnClass0 = {
    move: `bg-pink-600`,
    toggle: `bg-gray-600`,
    clone: `bg-blue-600`,
    trash: `bg-red-600`,
}

export const ETRAction = (cell, column) => {
    const result = []
    for (let i = 0; i < cell.length; i++) {
        const icon = `<i class="${icons[cell[i]]} w-4"></i>`
        const btnClass = btnClass0[cell[i]]
        result.push(`<button class="text-white text-xs border rounded p-1 ml-0.5 ${btnClass}">${icon}</button>`)
    }
    return `<span class="whitespace-nowrap">
        ${result.join("")}
    </span>`
}