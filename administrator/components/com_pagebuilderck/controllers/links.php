<?php
// No direct access
defined('_JEXEC') or die;

use Pagebuilderck\CKController;
use Pagebuilderck\CKFof;

$com_path = JPATH_SITE . '/components/com_content/';
JLoader::register('ContentHelperRoute', $com_path . 'helpers/route.php');

class PagebuilderckControllerLinks extends CKController {

	function __construct() {
		parent::__construct();
	}

	/**
	 * Load the articles and categories list
	 * 
	 * @return json
	 */
	public function ajaxShowArticles() {
		// security check
		CKFof::checkAjaxToken();

		$parentId = $this->input->get('parentid', 0, 'int');

		$model = $this->getModel();
		// $model = $this->getModel('Links', '', array());
		$categories = $model->getCategoriesById($parentId);
		$articles = $model->getArticlesByCategoryId($parentId);
		$items = array_merge($categories, $articles);
		?>
		<div class="cksubfolder">
		<?php
		// Access filter
		$access     = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		foreach ($items as $item) {
			$Itemid = ''; //test
			$hasChild = isset($item->rgt) && 
			(	(int)$item->rgt - (int)$item->lft > 1
				|| $item->counter > 0
			) ? true : false; // faire count articles
			$icon = isset($item->rgt) ? 'folder' : 'file';
			// check if category or article
			if ($item->type == 'article') {
				$item->slug    = $item->id . ':' . $item->alias;
				if ($access || in_array($item->access, $authorised))
				{
					// We know that user has the privilege to view the article
					$item->link = ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language);
				} else {
					continue;
				}
			} else {
				if ($access || in_array($item->access, $authorised))
				{
					$item->link =  ContentHelperRoute::getCategoryRoute($item->id);
				} else {
					continue;
				}
			}
		?>
			<div class="ckfoldertree parent">
				<div class="ckfoldertreetoggler <?php if (! $hasChild) { echo 'empty'; } ?>" onclick="ckLinksArticlesToggleTreeSub(this, <?php echo $item->id ?>)" ></div>
				<div class="ckfoldertreename hasTip" title="<?php echo $item->link ?>" onclick="ckSetLinksArticlesUrl('<?php echo $item->link ?>')"><span class="icon-<?php echo $icon ?>"></span><?php echo $item->title; ?>
				<?php if (isset($item->counter)) { ?><div class="ckfoldertreecount"><?php echo $item->counter ?></div><?php } ?>
				</div>
			</div>
		<?php
		}
		?>
		</div>
		<?php
		exit;
	}

	private function getCategories($parentId) {
		
	}

	private function getArticles($parentId) {
		$query = "SELECT id, title, alias, catid"
			. " FROM #__content"
			. " WHERE catid = " . (int)$parentId
			. " ORDER BY title ASC"
			;
		$articles = CKFof::dbLoadObjectList($query);
	}
}