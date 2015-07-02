<?php
class customPager extends CBasePager {
    public $jsCallback = "";
    public $searchParams = "";
	public $maxButtonCount=5;
    public $nextPageLabel;
	/**
	 * @var string the text label for the previous page button. Defaults to '&lt; Previous'.
	 */
	public $prevPageLabel;
	/**
	 * @var string the text label for the first page button. Defaults to '&lt;&lt; First'.
	 */
	public $firstPageLabel;
	/**
	 * @var string the text label for the last page button. Defaults to 'Last &gt;&gt;'.
	 */
	public $lastPageLabel;

	/**
	 * Initializes the pager by setting some default property values.
	 */
	public function init()
	{
		if($this->nextPageLabel===null)
			$this->nextPageLabel=Yii::t('yii','Next &gt;');
		if($this->prevPageLabel===null)
			$this->prevPageLabel=Yii::t('yii','&lt; Previous');
		if($this->firstPageLabel===null)
			$this->firstPageLabel=Yii::t('yii','&lt;&lt; First');
		if($this->lastPageLabel===null)
			$this->lastPageLabel=Yii::t('yii','Last &gt;&gt;');
	}

    protected function createPageButtons()
	{
		if(($pageCount=$this->getPageCount())<=1)
			return array();

		list($beginPage,$endPage)=$this->getPageRange();
		$currentPage=$this->getCurrentPage(false); // currentPage is calculated in getPageRange()
		$buttons=array();

		// first page
		if ($currentPage - 1 > 0)
			$buttons[]=$this->createPageButton($this->firstPageLabel,0,false);

		// prev page
		if(($page=$currentPage-1)>=0)
			$buttons[]=$this->createPageButton($this->prevPageLabel,$page,false);

		// internal pages
		for($i=$beginPage;$i<=$endPage;++$i)
			$buttons[]=$this->createPageButton($i+1,$i,$i==$currentPage);

		// next page
		if(($page=$currentPage+1) <= $pageCount-1)
			$buttons[]=$this->createPageButton($this->nextPageLabel,$page,false);

		// last page
		if ($currentPage + 1 < $pageCount - 1)
			$buttons[]=$this->createPageButton($this->lastPageLabel,$pageCount-1,false);

		return $buttons;
	}

	/**
	 * Creates a page button.
	 * You may override this method to customize the page buttons.
	 * @param string $label the text label for the button
	 * @param integer $page the page number
	 * @param string $class the CSS class for the page button.
	 * @param boolean $hidden whether this page button is visible
	 * @param boolean $selected whether this page button is selected
	 * @return string the generated button
	 */
	protected function createPageButton($label,$page,$selected)
	{
		$url = Yii::app()->createUrl(getCurrentRoute(),array_merge($_GET,$this->searchParams?$this->searchParams:array(),array('page'=>$page+1)));
		if ($this->jsCallback){
			$currentPage = $page + 1;
			$a = "<a href='javascript:{$this->jsCallback}({$currentPage})'>$label</a>";
		}else{
			$a = "<a href='$url'>$label</a>";
		}
		if($selected){
			return "<li class='active'><a href='javascript:void(0)'>$label</a></li>";
		}
		return "<li>$a</li>";
	}

	protected function getPageRange()
	{
		$currentPage=$this->getCurrentPage();
		$pageCount=$this->getPageCount();

		$beginPage=max(0, $currentPage-(int)($this->maxButtonCount/2));
		if(($endPage=$beginPage+$this->maxButtonCount-1)>=$pageCount)
		{
			$endPage=$pageCount-1;
			$beginPage=max(0,$endPage-$this->maxButtonCount+1);
		}
		return array($beginPage,$endPage);
	}
    public function run() {
    	$buttons=$this->createPageButtons();
    	if(empty($buttons))
    		return;
    	?>
    	<div class="pagination text-right">
    	    <ul>
    	        <?php
    	        echo join($buttons, "");
    	        ?>
    	    </ul>
    	</div>
    	<?php
    }
}