export interface ControlAttributeText {
    align?: 'left' | 'center' | 'right'
}
export interface ControlAttributeTextarea {
    align?: 'left' | 'center' | 'right'
    rowCount?: number
}
export interface ControlAttributeNumber {
    align?: 'left' | 'center' | 'right'
    decimalPlaces?: number
}
export interface ControlAttributeDropdown {
    allowClear?: boolean
    allowChooseWhenOneItem?: boolean
    allowOpen?: boolean
    selectedValue?: string
    dataSource?: Array<{ key: string; value: string }>
}
export interface ControlAttributeToggle {}
export interface ControlAttributeCheckbox {}
export interface ControlAttributePickerDatetime {
    align?: 'left' | 'center' | 'right'
    subType: 'date' | 'time' | 'datetime'
}
