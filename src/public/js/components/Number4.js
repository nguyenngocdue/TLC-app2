const changeBgColor = (e, tableId) => {
    const id = e.id
    const hours = getEById(id).val()
    const filedName = getFieldNameInTable01FormatJS(e.name, tableId)
    // console.log('ABC', filedName, hours, e)
    if (['month_remaining_hours', 'year_remaining_hours'].includes(filedName)) {
        // console.log(id, hours)
        const max = filedName === 'month_remaining_hours' ? 40 : 200
        const value = hours / max * 100
        let color = 'bg-gray-100'
        switch (true) {
            case value < 0: color = 'bg-red-600'; break
            case value <= 25: color = 'bg-pink-400'; break
            case value <= 50: color = 'bg-orange-300'; break
            case value <= 75: color = 'bg-yellow-300'; break
            case value > 75: color = 'bg-green-300'; break
        }
        document.getElementById(id).classList.remove(...[
            'readonly',
            'bg-gray-100',
            'bg-red-600',
            'bg-pink-400',
            'bg-yellow-300',
            'bg-orange-300',
            'bg-green-300',
        ])
        document.getElementById(id).classList.add(color)
    }
}