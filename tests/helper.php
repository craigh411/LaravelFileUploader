<?
/**
 * Mocks Laravels public_path() helper function which is used in LaravelFileUploader

 */
if(! function_exists('public_path'))
{
	function public_path()
	{
		return 'public';
	}
}
