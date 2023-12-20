const icons = {
    move: `fa-solid fa-grip-vertical text-white`,
    toggle: `fa-solid fa-square-check text-white`,
    duplicate: `fa-solid fa-copy text-white`,
    trash: `fa-solid fa-trash text-white`,
}

const btnClass0 = {
    move: `bg-pink-600 text-white`,
    toggle: `bg-gray-600 text-white`,
    duplicate: `bg-blue-600 text-white`,
    trash: `bg-red-600 text-white`,
}

export const ETAction = (cell) => {
    const result = []
    for (let i = 0; i < cell.length; i++) {
        const icon = `<i class="${icons[cell[i]]} w-4"></i>`
        const btnClass = btnClass0[cell[i]]
        result.push(`<button class="border rounded p-1 ml-0.5 ${btnClass}">${icon}</button>`)
    }
    return result.join("")
}