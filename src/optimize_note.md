HasCheckbox
Cache table Field to ram by singleton:
HR_OTR
Before:
156 query, 1500ms (20/page)
721 query, 8039ms (100/page)
After:
117 query, 1427ms (20/page) + 4.89% Faster
522 query, 7350ms (100/page) + 8.57% Faster
Index pair of column (doc_type + doc_id) / (term_type + term_id)
HR_OTR
Before:
156 query, 1552ms (20/page)
721 query, 8838ms (100/page)
After:
158 query, 846ms (20/page) + 45.49% Faster
721 query, 4605ms (100/page) + 47.90% Faster

Eager load in Model:
HR_OTR ('getWorkplace', 'getAssignee1', 'getHrOtLines', 'getOwnerId')
Before:
156 query, 1545ms (20/page)
723 query, 7773ms (100/page)
After:
86 query, 1326ms (20/page) + 14.17% Faster
329 query, 6589ms (100/page) + 15.23% Faster

Eager load in ViewAll getDataSource:
HR_OTR ('getWorkplace', 'getAssignee1', 'getHrOtLines', 'getOwnerId')
Before:
156 query, 1545ms (20/page)
723 query, 7773ms (100/page)
After:
80 query, 1307ms (20/page) + 15.40% Faster
329 query, 6627ms (100/page) + 14.74% Faster
NCR
Before:
221 query, 1228ms (20/page)
1010 query, 9529ms (100/page)
After:(belongsTo, hasMany)
81 query, 1107ms (20/page) + 9.85% Faster
329 query, 6591ms (100/page) + 30.83% Faster
After:(belongsTo)
78 query, 900ms (20/page) + 26.7% Faster
206 query, 4886ms (100/page) + 48.7% Faster

StrDB Cache Project and sub project to ram:
NCR:
Before:
221 query, 1228ms (20/page)
1010 query, 9529ms (100/page)
After:
202 query, 1174ms (20/page) + 4.40% Faster
911 query, 7032ms (100/page) + 26.20% Faster

TraitTableRows:
Cache eloquent to ram by singleton:
$rawData = $dataLineObj->$dataIndex ?? "";
to
$rawData = static::getEloquent($dataLineObj, $dataIndex);
==> Does not work, same latency

Applying 4 optimizations:
HR_OTR
Before:
156 query, 1545ms (20/page)
723 query, 7773ms (100/page)
After:
62 query, 736ms (20/page) + 52.36% Faster
227 query, 4411ms (100/page) + 43.25% Faster
NCR:
Before:
60 query, 1006ms (20/page)
1010 query, 9529ms (100/page)
After:
59 query, 924ms (20/page) + 8.15% Faster
107 query, 4355ms (100/page) + 54.29% Faster

Cache users table to ram for dropdown3
NCR:
Before:
4 query, 383ms
After:
1 query, 249ms +34.99 %faster

Cache avatar to ram for user->avatar,
HR OTR:
Before: (50/page)
268 query, 2871 ms
After: (50/page)
188 query, 2526 ms +12% faster
108 query, 2324 ms +19% faster

CurrentUser::getPermissions()
Before: 300 ms
After: 1.56 ms

Timesheet line: 7.54s (3295 query, 12497 models)
Cache to ram $model::whereIn('id', $ids0)->get(): 5.43s (2012 query, 4314 models)
Don't query $modelPath::find($row['id']); 3.51s (1140 query, 3248 models)
Cache to ram DB::table('many_to_many')->where: 2.81s (745 query, 3248 models)
Query many_to_many 2 condition => only query one condition and cluster cache the results: 2.16s (286 query, 3248 models)
$model::whereIn => query and cache whole table => 1.57s (42 query, 1752 models)

OTR View All 1.38s (68 query, 2031 models)
Cache avatar table to ram: 1.32s, (46 query, 2013 models)