"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.getConfigJson = exports.getConfigFormat = exports.defaultWidth = void 0;
const defaultWidth = (pickerType) => {
    switch (pickerType) {
        case 'datetime':
            return 120;
        case 'date':
            return 80;
        case 'time':
            return 50;
        case 'month':
            return 60;
        case 'year':
            return 50;
        default:
            return 100;
    }
};
exports.defaultWidth = defaultWidth;
const getConfigFormat = (pickerType) => {
    switch (pickerType) {
        case 'datetime':
            return 'YYYY-MM-DD HH:mm:ss';
        case 'date':
        case 'month':
        case 'year':
            return 'YYYY-MM-DD';
        case 'time':
            return 'HH:mm:ss';
        default:
            throw new Error(`Unsupported picker type: ${pickerType}`);
    }
};
exports.getConfigFormat = getConfigFormat;
const getConfigJson = (pickerType) => {
    const defaultConfig = {
        altInput: true,
        weekNumbers: true,
        time_24hr: true,
        allowInput: true,
        defaultDate: new Date(),
    };
    switch (pickerType) {
        case 'datetime':
            return Object.assign(Object.assign({}, defaultConfig), { enableTime: true, altFormat: 'd/m/Y H:i', dateFormat: 'Y-m-d H:i:S' });
        case 'date':
            return Object.assign(Object.assign({}, defaultConfig), { enableTime: false, altFormat: 'd/m/Y', dateFormat: 'Y-m-d' });
        case 'time':
            return Object.assign(Object.assign({}, defaultConfig), { enableTime: true, noCalendar: true, altFormat: 'H:i', dateFormat: 'H:i:S' });
        case 'month':
            return Object.assign(Object.assign({}, defaultConfig), { enableTime: false, noCalendar: !true, altFormat: 'm/Y', dateFormat: 'Y-m-d' });
        case 'year':
            return Object.assign(Object.assign({}, defaultConfig), { enableTime: false, noCalendar: !true, altFormat: 'Y', dateFormat: 'Y-m-d' });
        default:
            throw new Error(`Unsupported picker type: ${pickerType}`);
    }
};
exports.getConfigJson = getConfigJson;
//# sourceMappingURL=Shared.js.map