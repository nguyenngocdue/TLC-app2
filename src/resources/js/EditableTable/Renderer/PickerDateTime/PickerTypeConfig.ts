export const getConfigJson = (pickerType: string) => {
    const defaultConfig = {
        altInput: true,
        weekNumbers: true,
        time_24hr: true,
        allowInput: true,
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
                noCalendar: false,
                altFormat: 'm/Y',
                dateFormat: 'Y-m',
            }
        case 'year':
            return {
                ...defaultConfig,
                enableTime: false,
                noCalendar: false,
                altFormat: 'Y',
                dateFormat: 'Y',
            }
        default:
            throw new Error(`Unsupported picker type: ${pickerType}`)
    }
}
