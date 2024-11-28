<?php

namespace App\Utils;

class ClassList
{
    const TEXT     = "(CLASSLIST-TEXT)     block w-full text-gray-700 rounded-md border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 sm:text-sm focus:border-1 focus:border-blue-600 dark:focus:border-blue-600 focus:outline-none px-2 py-2 h-[42px] (END-OF-CLASSLIST)";
    const TEXT3     = "(CLASSLIST-TEXT3)     block w-full text-gray-700 rounded-md border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 sm:text-sm focus:border-1 focus:border-blue-600 dark:focus:border-blue-600 focus:outline-none px-2 py-2 h-[38px] (END-OF-CLASSLIST)";
    const TEXTAREA = "(CLASSLIST-TEXTAREA) block w-full text-gray-700 rounded-md border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 sm:text-sm focus:border-1 focus:border-blue-600 dark:focus:border-blue-600 focus:outline-none px-2 py-2 (END-OF-CLASSLIST)";
    const DROPDOWN = "(CLASSLIST-DROPDOWN) block w-full text-gray-700 rounded-md border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 sm:text-sm focus:border-1 focus:border-blue-600 dark:focus:border-blue-600 focus:outline-none px-2 py-2 (END-OF-CLASSLIST)";
    const DROPDOWN_FAKE = "(DROPDOWN_FAKE) block w-full text-gray-700 rounded-md border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 sm:text-sm focus:border-1 focus:border-blue-600 dark:focus:border-blue-600 focus:outline-none px-[10px] py-[10px] h-[42px] (END-OF-DROPDOWN_FAKE)";

    const TOGGLE   = "(CLASSLIST-TOGGLE) w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 (END-OF-CLASSLIST)";
    const TOGGLE_CHECKBOX = "(CLASSLIST-CB-TOGGLE) checked:bg-blue-600 checked:border-blue-600 (END-OF-CB-TOGGLE)";
    const RADIO_CHECKBOX = "(CLASSLIST-RADIO-CHECKBOX) grid grid-cols-12 gap-2 bg-white border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 (END-OF-CLASSLIST)";
    const RADIO_GROUP = "(CLASSLIST-RADIO-GROUP) grid grid-cols-5 gap-2 bg-white border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 (END-OF-CLASSLIST)";
    const BUTTON = "(CLASSLIST-BUTTON) font-medium leading-tight rounded transition duration-150 ease-in-out focus:ring-0 focus:outline-n1one (END-OF-CLASSLIST)";

    const ADV_FLT_INPUT_GROUP_TEXT = "input-group-text border border-gray-300 text-gray-900 rounded-md p-2.5 dark:placeholder-gray-400 block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:outline-none focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input";
    const ADV_FLT_INPUT_GROUP_TEXT3 = "input-group-text border border-gray-300 text-gray-900 rounded-md p-1.5 dark:placeholder-gray-400 block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:outline-none focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input";
    const ADV_FLT_FORM_INPUT = "form-control float-right bg-white border border-gray-300 text-gray-900 rounded-md p-2.5 dark:placeholder-gray-400 block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:outline-none focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input";
    const ADV_FLT_FORM_INPUT3 = "form-control float-right bg-white border border-gray-300 text-gray-900 rounded-md p-1.5 dark:placeholder-gray-400 block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:outline-none focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input";
    const BUTTON2 = "(CLASSLIST-BUTTON2) px-2.5 py-2 inline-block font-medium text-sm leading-tight rounded focus:ring-0 transition duration-150 ease-in-out bg-purple-600 text-white shadow-md hover:bg-purple-700 hover:shadow-lg focus:bg-purple-700 focus:shadow-lg focus:outline-none active:bg-purple-800 active:shadow-lg";

    const BUTTON_KANBAN_ELLIPSIS = "border border-1 px-1 ml-1 rounded cursor-pointer hover:bg-blue-400";
}
