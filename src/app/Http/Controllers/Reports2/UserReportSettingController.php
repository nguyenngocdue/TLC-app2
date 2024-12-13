<?php

namespace App\Http\Controllers\Reports2;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UserReportSettingController extends Controller
{
    protected $statuses_path = "";

    private $type="rp_report";

    function getType()
    {
        return $this->type;
    }

    private function processUserSettings($userSettings, $types)
    {
        $rpKeyInUsers = [];
        foreach ($userSettings as $userId => $settings) {
            if (!is_array($settings)) {
                continue;
            }
            foreach ($settings as $entity => $rpTypes) {
                if (!is_array($rpTypes)) {
                    continue;
                }
                foreach ($rpTypes as $rpType => $values) {
                    if (!in_array($rpType, $types, true)) {
                        continue;
                    }
                    foreach ((array)$values as $rpNum => $rp) {
                        $keyMap = sprintf('%s-%s_%s', Str::singular($rpType), Str::singular($entity), $rpNum);
                        $rpKeyInUsers[$userId][$keyMap] = [
                            'entity' => $entity,
                            'rp_type' => $rpType,
                            'rp_num' => $rpNum,
                        ];
                    }
                }
            }
        }
        return $rpKeyInUsers;
    }

    private function makeDatasourceFromReportJson($data, $status)
    {
        $result = [];
        foreach ($data as $values) {
            $rpType = explode('-', $values['name'])[0];
            $result[] = [
                'rp_name' => "<a class='text-blue-800' target='_blank' href='" . route($values['name']) . "'>" . htmlspecialchars($values['name']) . "</a>",
                'title' => $values['title'],
                'package_tab' => $values['package_tab'],
                'rp_type' => $rpType,
                'status' => "<p class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 cursor-none'>{$status}</p>"
            ];
        }
        return $result;
    }

    private function requireDataSource($data)
    {
        $result = [];
        foreach ($data as $userId => $values) {
            $fullName = User::find($userId)->full_name;
            foreach ($values as $key => $value) {
                $result[] = [
                    'user_full_name' => "<a class='text-blue-800' target='_blank' href='" . route('users.edit', $userId) . "'>" . htmlspecialchars($fullName) . "</a>",
                    'rp_name' => "<a class='text-blue-800' target='_blank' href='" . route($key) . "'>" . htmlspecialchars($key) . "</a>",
                    'rp_type' => $value['rp_type'],
                    'rp_num' => $value['rp_num'],
                    'entity' => $value['entity'],
                    'status' => '<p class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 cursor-none">Not yet hidden in Report v1</p>'
                ];
            }
        }
        return $result;
    }

    private function getColumnDataSource1()
    {
        return [
            [
                'title' => 'Report Name',
                'dataIndex' => 'rp_name',
                'align' => 'left',
                'width' => 200
            ],
            [
                'title' => 'Report Title',
                'dataIndex' => 'title',
                'align' => 'left',
                'width' => 200
            ],
            [
                'title' => 'Report Type',
                'dataIndex' => 'rp_type',
                'align' => 'left',
                'width' => 200
            ],
            [
                'title' => 'Status',
                'dataIndex' => 'status',
                'align' => 'center',
                'width' => 200
            ]
           
        ];
    }

    private function getColumnDataSource2()
    {
        return [
            [
                'title' => 'Full Name',
                'dataIndex' => 'user_full_name',
                'align' => 'left',
                'width' => 200
            ],
            [
                'title' => 'Report Type',
                'dataIndex' => 'rp_type',
                'align' => 'left',
                'width' => 200
            ],
            [
                'title' => 'Report Name',
                'dataIndex' => 'rp_name',
                'align' => 'left',
                'width' => 200
            ],
            [
                'title' => 'Entity',
                'dataIndex' => 'entity',
                'align' => 'left',
                'width' => 200
            ]
           
           
        ];
    }


    private function getUsersNeedToCheck($hiddenRps, $processedData)
    {
        $usersNeedToCheck = [];
        foreach (array_keys($hiddenRps) as $rpKey) {
            foreach ($processedData as $userId => $data) {
                if (array_key_exists($rpKey, $data)) {
                    $usersNeedToCheck[$userId][$rpKey] = $data[$rpKey];
                }
            }
        }
        return $usersNeedToCheck;
    }

    function index(Request $request)
    {
        $path = storage_path() . "/json/workflow/reports.json";
        $jsonData = json_decode(file_get_contents($path), true);

        [$hiddenRps, $openingRps] = [[], []];
        foreach ($jsonData as $rpKey => $values) {
            if (isset($values['hidden']) && $values['hidden'] == true) {
                $hiddenRps[$rpKey] = $values;
            } else $openingRps[$rpKey] = $values;
        }

        $types = ['documents', 'registers', 'reports'];
        $userSettings = User::all()->pluck('settings', 'id');

        $processedData = self::processUserSettings($userSettings, $types);

        $usersNeedToCheck = self::getUsersNeedToCheck($hiddenRps, $processedData);

        // make data to render table
        $openingRpData = self::makeDatasourceFromReportJson($openingRps, 'Not yet hidden in Report 1.');
        $hiddenRpData = self::makeDatasourceFromReportJson($hiddenRps,'Already hidden in Report 1.');
        $queriedUsers = self::requireDataSource($usersNeedToCheck);

        $dataSource = [
            'openingRpData' => [
                'title' => 'Statistics of reports <span class="text-orange-400">hidden </span> in Report v1.',
                'tableDataSource' => $hiddenRpData,
                'tableColumns' => self::getColumnDataSource1()
            ],
            'hiddenRpData' => [
                'title' => 'Statistics of reports <span class="text-orange-400">not yet hidden </span> in Report v1.',
                'tableDataSource' => $openingRpData,
                'tableColumns' => self::getColumnDataSource1()
            ],
            'queriedUsers' => [
                'title' => 'Data of reports <span class="text-orange-400">not yet deleted</span> in UserSetting <br/> when you have already set them to hidden in Report v1.',
                'tableDataSource' => $queriedUsers,
                'tableColumns' => self::getColumnDataSource2(),
                'usersNeedToCheck' => $usersNeedToCheck
            ],
        ];
        $routeUpdate = route('rp_reset_user_setting');
        $token = CurrentUser::getTokenForApi();
        
        return view('components.reports2.user-report-setting', [
            'data' => $dataSource,
            'routeUpdate' => $routeUpdate,
            'token' => $token
        ]);
    }

    public function update(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'users' => 'required|array',
        ]);
    
        $selectedUsers = $validated['users'];
    
        // Fetch all users from the database that match the given IDs
        $users = User::whereIn('id', array_keys($selectedUsers))->get()->keyBy('id');
    
        foreach ($selectedUsers as $userId => $selectedUser) {
            if ($users->has($userId)) {
                foreach ($selectedUser as $keys) {
                    if (!isset($keys['entity'], $keys['rp_type'], $keys['rp_num'])) continue;
    
                    $entity = $keys['entity'];
                    $rpType = $keys['rp_type'];
                    $rpNum = $keys['rp_num'];
    
                    // Get current user settings
                    $currentUser = $users[$userId]->settings;
    
                    // Check and unset the required nested keys
                    if (isset($currentUser[$entity][$rpType][$rpNum])) {
                        unset($currentUser[$entity][$rpType][$rpNum]);
                    }
                    // Update user settings
                    $users[$userId]->update(['settings' => $currentUser]);
                }
            }
        }
        return response()->json(['message' => 'Users updated successfully.']);
    }
    
    
}
