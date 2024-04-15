<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Setting extends Model
{
    protected $table = 'settings';
    protected static $tbl = 'settings';
    protected $primaryKey = 'setting_id';    

    public static function getSetting($column, $value)
    {
        $value = $column == 'setting_id' || $column == 'settings.setting_id' ? decode($value) : $value;
        $query = Self::where($column, $value)->where('status', 1)->first();
        return $query ? $query->toArray() : emptyTableColumns(Self::$tbl);
    }

    public static function getSettingsByCategory($category)
    {
        $query = Self::whereNotNull('settings.setting_id');
        $query->where('employer_id', employerId());        
        $query->where('category', $category);
        $query->orderBy('setting_id', 'ASC');
        $query = $query->get();
        return $query ? $query->toArray() : array();
    }

    public static function updateData($data)
    {
        unset($data['_token']);
        $employerId = employerId();
        
        foreach ($data as $k => $d) {
            $d = removeUselessLineBreaks($d);
            $condition = array('key' => $k, 'employer_id' => $employerId);
            $existing = Self::where($condition)->first();
            if ($existing) {
                Self::where($condition)->update(array('value' => $d));
            } else {
                $insert = array_merge($condition, array('value' => $d));
                Self::insert($insert);
            }
        }
    }

    public static function updateCssVariables($variable, $value)
    {   
        //Getting existing variables and adding new one
        $variables = array();

        $cssVars = ":root {\n";

        if (is_array($variable)) {
            foreach ($variable as $k => $var) {
                $variables[$var] = $value[$k];
            }
        } else {
            $variables[$variable] = $value;
        }

        foreach ($variables as $key => $value) {
            $cssVars .= '--'.$key.':'.$value.";\n";
        }
        $cssVars .= "}";

        //Writing to file
        $dir_path = base_path().'/public/'.employerPath().'/variables.css';
        createDirectoryIfNotExists($dir_path);
        $file = fopen($dir_path, "w");
        fwrite($file, $cssVars);
        fclose($file);

        //Saving to settings
        Self::updateData(array('css_variables' => json_encode($variables)));
    }
}