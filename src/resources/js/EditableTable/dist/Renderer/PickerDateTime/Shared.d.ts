export declare const defaultWidth: (pickerType: string) => 50 | 100 | 120 | 80 | 60;
export declare const getConfigFormat: (pickerType: string) => "YYYY-MM-DD HH:mm:ss" | "YYYY-MM-DD" | "HH:mm:ss";
export declare const getConfigJson: (pickerType: string) => {
    enableTime: boolean;
    altFormat: string;
    dateFormat: string;
    altInput: boolean;
    weekNumbers: boolean;
    time_24hr: boolean;
    allowInput: boolean;
    defaultDate: Date;
} | {
    enableTime: boolean;
    noCalendar: boolean;
    altFormat: string;
    dateFormat: string;
    altInput: boolean;
    weekNumbers: boolean;
    time_24hr: boolean;
    allowInput: boolean;
    defaultDate: Date;
};
//# sourceMappingURL=Shared.d.ts.map