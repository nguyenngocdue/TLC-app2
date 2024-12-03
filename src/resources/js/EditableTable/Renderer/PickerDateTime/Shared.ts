export const defaultWidth = (pickerType: string) => {
    switch (pickerType) {
        case 'datetime':
            return 120
        case 'date':
            return 80
        case 'time':
            return 50
        case 'month':
            return 60
        case 'year':
            return 50
        default:
            return 100
    }
}

export const getConfigFormat = (pickerType: string) => {
    switch (pickerType) {
        case 'datetime':
            return 'YYYY-MM-DD HH:mm:ss'
        case 'date':
        case 'month':
        case 'year':
            return 'YYYY-MM-DD'
        case 'time':
            return 'HH:mm:ss'
        default:
            throw new Error(`Unsupported picker type: ${pickerType}`)
    }
}

export const getConfigJson = (pickerType: string) => {
    const defaultConfig = {
        altInput: true,
        weekNumbers: true,
        time_24hr: true,
        allowInput: true,
        defaultDate: new Date(),
    }

    switch (pickerType) {
        case 'datetime':
            return {
                ...defaultConfig,
                enableTime: true,
                altFormat: 'd/m/Y H:i',
                dateFormat: 'Y-m-d H:i:S',
            }
        case 'date':
            return {
                ...defaultConfig,
                enableTime: false,
                altFormat: 'd/m/Y',
                dateFormat: 'Y-m-d',
            }
        case 'time':
            return {
                ...defaultConfig,
                enableTime: true,
                noCalendar: true,
                altFormat: 'H:i',
                dateFormat: 'H:i:S',
            }
        case 'month':
            return {
                ...defaultConfig,
                enableTime: false,
                noCalendar: !true,
                altFormat: 'm/Y',
                dateFormat: 'Y-m-d',
            }
        case 'year':
            return {
                ...defaultConfig,
                enableTime: false,
                noCalendar: !true,
                altFormat: 'Y',
                dateFormat: 'Y-m-d',
            }
        default:
            throw new Error(`Unsupported picker type: ${pickerType}`)
    }
}
