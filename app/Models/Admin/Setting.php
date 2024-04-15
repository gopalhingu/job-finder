<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Setting extends Model
{
    protected $table = 'settings';
    protected static $tbl = 'settings';
    protected $primaryKey = 'setting_id';

    protected $fillable = [
        'setting_id',
        'employer_id',
        'category',
        'key',
        'value',
    ];

    public static function getMembership($column, $value)
    {
    	$setting = Self::where($column, $value)->first();
    	return $setting ? $setting : emptyTableColumns(Self::$tbl);
    }

    public static function storeMembership($data, $edit = null)
    {
        unset($data['setting_id'], $data['_token']);
        if ($edit) {
            $data['updated_at'] = date('Y-m-d G:i:s');
            Self::where('setting_id', $edit)->update($data);
        } else {
            $data['created_at'] = date('Y-m-d G:i:s');
            Self::insert($data);
        }
    }

    public static function updateData($data)
    {
        foreach ($data as $k => $v) {
            $v = removeUselessLineBreaks($v);
            $condition = array('employer_id' => 0, 'key' => $k);
            if (Self::where($condition)->first()) {
                Self::where($condition)->update(array('value' => $v));
            } else {
                Self::insert(array_merge($condition, array('value' => $v)));
            }
        }
    }

    public static function updateCssVariables($variables)
    {   
        //Main/Front site css variables
        $existing = setting('css_variables');
        $existing = $existing ? objToArr(json_decode($existing)) : array();

        //Merging and updating
        $merged = array_merge($variables, $existing);
        $updated = array();
        foreach ($merged as $var => $val) {
            $updated[$var] = isset($variables[$var]) ? $variables[$var] : $val;
        }

        $cssVars = ":root {\n";
        foreach ($updated as $key => $value) {
            $cssVars .= '--'.$key.':'.$value.";\n";
        }
        $cssVars .= "}";

        //Writing to file and update in db setting
        writeToFile(public_path('/f-assets'.viewPrfx(true).'/css/variables.css'), $cssVars);
        Self::updateData(array('css_variables' => json_encode($updated)));

        //Candidate site css variables
        $existing2 = setting('css_variables_candidate');
        $existing2 = $existing2 ? objToArr(json_decode($existing2)) : array();
        $updated2 = array();
        $vals = array('body-bg', 'main-menu-bg', 'main-menu-font-color', 'main-menu-font-highlight-color', 'main-banner-bg', 'main-banner-height', 'main-banner-height', 'breadcrumb-image', 'main-banner');
        foreach ($vals as $val) {
            $new = issetVal($variables, $val);
            $old = issetVal($existing2, $val);
            $updated2[$val] = $new ? $new : $old;
        }

        $cssVars2 = ":root {\n";
        foreach ($updated2 as $key => $value) {
            $cssVars2 .= '--'.$key.':'.$value.";\n";
        }
        $cssVars2 .= "}";

        //Writing to file and update in db setting
        writeToFile(public_path('/c-assets'.viewPrfx(true).'/css/variables.css'), $cssVars2);
        Self::updateData(array('css_variables_candidate' => json_encode($updated2)));
    }    
}