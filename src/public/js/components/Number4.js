const changeBgColor = (e, tableId) => {
    const id = e.id
    const value = getEById(id).val()
    const filedName = getFieldNameInTable01FormatJS(e.name, tableId)
    // console.log('ABC', filedName, value, e)
    if (filedName === 'remaining_hours') {
        // console.log(id, value)
        let color = 'bg-gray-100'
        switch (true) {
            case value < 0: color = 'bg-red-600'; break
            case value < 10: color = 'bg-red-400'; break
            case value < 20: color = 'bg-orange-300'; break
            case value < 30: color = 'bg-yellow-300'; break
            case value < 40: color = 'bg-green-300'; break
        }
        document.getElementById(id).classList.remove(...[
            'readonly',
            'bg-gray-100',
            'bg-red-600',
            'bg-red-400',
            'bg-yellow-300',
            'bg-orange-300',
            'bg-green-300',
        ])
        document.getElementById(id).classList.add(color)
    }
}