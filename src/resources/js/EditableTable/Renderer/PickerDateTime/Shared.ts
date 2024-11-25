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
