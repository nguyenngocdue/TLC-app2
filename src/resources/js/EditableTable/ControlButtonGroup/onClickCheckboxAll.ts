const state: { [tableName: string]: number } = {}

export const onClickCheckboxAll = (tableName: string) => {
    const checkboxAll = document.getElementById(`${tableName}__checkbox_all`) as HTMLInputElement

    if (state[tableName] === undefined) {
        state[tableName] = 0
    } else {
        state[tableName] = (state[tableName] + 1) % 3
    }

    switch (state[tableName]) {
        case 0:
            checkboxAll.checked = false
            checkboxAll.indeterminate = false
            break
        case 1:
            checkboxAll.checked = false
            checkboxAll.indeterminate = true
            break
        case 2:
            checkboxAll.checked = true
            checkboxAll.indeterminate = false
            break
    }

    // const checkboxes = document.querySelectorAll(
    //     `#${tableName} #${tableName}__cb__`,
    // ) as NodeListOf<HTMLInputElement>

    // console.log('checkboxAll', checkboxAll.checked)
    // checkboxes.forEach((checkbox) => {
    //     checkbox.checked = checkboxAll.checked
    // })
}
