<?php
/**
 * Created by PhpStorm.
 * User: jiangcoco
 * Date: 2017/5/4
 * Time: 23:41
 */
namespace AppBundle\Twig;
use Doctrine\Common\Collections\Criteria;
class AppExtension extends \Twig_Extension
{

    protected $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('roles', array($this, 'rolesFilter')),
            new \Twig_SimpleFilter('isPublic', array($this, 'isPublicFilter')),
            new \Twig_SimpleFilter('isFinished', array($this, 'isFinishedFilter')),
            new \Twig_SimpleFilter('level', array($this, 'levelFilter')),
            new \Twig_SimpleFilter('unescape', array($this, 'unescapeFilter')),
            new \Twig_SimpleFilter('duration', array($this, 'videoDurationFilter')),
            new \Twig_SimpleFilter('name', array($this, 'nameFilter')),
            new \Twig_SimpleFilter('questionType', array($this, 'questionTypeFilter')),
            new \Twig_SimpleFilter('commentNum', array($this, 'commentNumFilter')),
            new \Twig_SimpleFilter('noteNum', array($this, 'noteNumFilter')),
            new \Twig_SimpleFilter('totalLessons', array($this, 'totalLessonsFilter')),
            new \Twig_SimpleFilter('recodes', array($this, 'recodesFilter')),
            new \Twig_SimpleFilter('isTop', array($this, 'isTopFilter')),
            new \Twig_SimpleFilter('state', array($this, 'stateFilter')),
            new \Twig_SimpleFilter('singlescoreRate', array($this, 'singlescoreRateFilter')),
            new \Twig_SimpleFilter('multiplescoreRate', array($this, 'multiplescoreRateFilter')),
            new \Twig_SimpleFilter('toLetter', array($this, 'toLetterFilter')),
            new \Twig_SimpleFilter('isReaded', array($this, 'isReadedFilter')),
            new \Twig_SimpleFilter('replayName', array($this, 'replayNameFilter')),
            new \Twig_SimpleFilter('isAssignmentFinished', array($this, 'isAssignmentFinishedFilter')),
            new \Twig_SimpleFilter('hasNewReplay', array($this, 'hasNewReplayFilter')),
            new \Twig_SimpleFilter('getNameByUid', array($this, 'getNameByUidFilter')),
        );
    }

    public function rolesFilter($role)
    {

    if ($role ==='ROLE_ADMIN'){
        $role = '管理员';
    }else if ($role ==='ROLE_ADVISOR'){
        $role = '企业导师';
    }else if ($role ==='ROLE_TEACHER'){
        $role = '学校教师';
    }else if ($role ==='ROLE_STUDENT'){
        $role = '学生';
    }
        return $role;
    }

    public function isPublicFilter($isPublic)
    {
        if ($isPublic === 0){
            $isPublic = '完全公开';
        }else if ($isPublic === 1){
            $isPublic = '部分公开';
        }
        return $isPublic;
    }

    public function isReadedFilter($isReaded)
    {
        if ($isReaded == 0){
            $isReaded = '未阅读';
        }else if ($isReaded == 1){
            $isReaded = '已阅读';
        }
        return $isReaded;
    }

    public function isAssignmentFinishedFilter($isAssignmentFinished)
    {
        if ($isAssignmentFinished == 0){
            $isAssignmentFinished = '未完成';
        }else if ($isAssignmentFinished == 1){
            $isAssignmentFinished = '已完成';
        }
        return $isAssignmentFinished;
    }

    public function isTopFilter($isTop)
    {
      if ($isTop == '1'){
            $isTop = '已推荐';
        }
        return $isTop;
    }
    public function isFinishedFilter($isFinished)
    {
        if ($isFinished === 0){
            $isFinished = '进行中';
        }else if ($isFinished === 1){
            $isFinished = '已完结';
        }
        return $isFinished;
    }

    public function levelFilter($level)
    {
        if ($level == 0){
            $level = '简单';
        }else if ($level == 1){
            $level = '中等';
        }else if ($level == 2){
            $level = '困难';
        }
        return $level;
    }
    public function questionTypeFilter($type)
    {
        if ($type == 0){
            $type = '单选题';
        }else if ($type == 1){
            $type = '多选题';
        }else if ($type == 2){
            $type = '判断题';
        }
        return $type;
    }

    public function unescapeFilter($unescape)
    {
        return html_entity_decode($unescape);
    }
    public function videoDurationFilter($duration)
    {
        if($duration){
            if($duration < 60) return '00:'.$duration;
            $minutes = intval($duration / 60);
            if($minutes < 10){
                $minutes = '0'.$minutes;
            }
            $seconds = $duration % 60;
            if($seconds < 10){
                $seconds = '0'.$seconds;
            }
            return $minutes.':'.$seconds;
        }else{
            return $duration;
        }

    }

    public function replayNameFilter($replayName)
    {
        $user = $this->em->getRepository('AppBundle:User')->find($replayName);
        $role = $user->getRoles()[0];
        if($role =='ROLE_TEACHER'){
            return '教师';
        }else{
            return '学生';
        }

    }

    public function nameFilter($name)
    {
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(array('username'=>$name));
        $role = $user->getRoles()[0];
        if($role =='ROLE_TEACHER'){
            $student = $this->em->getRepository('AppBundle:Teacher')->findOneBy(array('number'=>$name));
            return $student->getName().'(教师)';
        }else if($role =='ROLE_STUDENT'){
            $student = $this->em->getRepository('AppBundle:Student')->findOneBy(array('number'=>$name));
            return $student->getName().'(学生)';
        }else if($role =='ROLE_ADVISOR'){
            $student = $this->em->getRepository('AppBundle:Advisor')->findOneBy(array('number'=>$name));
            return $student->getName().'(导师)';
        }else{
            return $name;
        }

    }

    public function getNameByUidFilter($id)
    {
        $user = $this->em->getRepository('AppBundle:User')->find($id);
        $role = $user->getRoles()[0];
        if($role =='ROLE_TEACHER'){
            $teacher = $this->em->getRepository('AppBundle:Teacher')->findOneBy(array('number'=>$user->getUsername()));
            return $teacher->getName().'(教师)';
        }else if($role =='ROLE_ADVISOR'){
            $advisor = $this->em->getRepository('AppBundle:Advisor')->findOneBy(array('number'=>$user->getUsername()));
            return $advisor->getName().'(导师)';
        }else{
            return '未知错误';
        }

    }
    public function commentNumFilter($number)
    {
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(array('username'=>$number));
        return $user->getComments();
    }
    public function noteNumFilter($number,$groupId)
    {
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(array('username'=>$number));
        $notes = $user->getNotes();
        $group = $this->em->getRepository('AppBundle:Group')->find($groupId);
        $course = $group->getCourse();
        $criteria = Criteria::create()->where(Criteria::expr()->eq("course", $course));
        return $notes->matching($criteria);

    }
    public function hasNewReplayFilter($id,$number,$tid)
    {
        $teacherLastReplay = $this->em->getRepository('AppBundle:AssignmentReplay')
            ->findOneBy(array('assignment'=>$id,'uid'=>$tid),array('id' => 'DESC'));
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(array('username'=>$number));
        $studentLastReplay = $this->em->getRepository('AppBundle:AssignmentReplay')
            ->findOneBy(array('assignment'=>$id,'uid'=>$user->getId()),array('id' => 'DESC'));
        if($studentLastReplay){
            if($teacherLastReplay->getCreatedTime()<$studentLastReplay->getCreatedTime()){
                return 1;
            }else{
                return 0;
            }
        }
        return 0;


    }
    public function recodesFilter($recodes,$groupId)
    {
        $group = $this->em->getRepository('AppBundle:Group')->find($groupId);
        $course = $group->getCourse();
        $criteria = Criteria::create()->where(Criteria::expr()->eq("course", $course));
        return count($recodes->matching($criteria));

    }

    public function totalLessonsFilter($groupId)
    {
        $group = $this->em->getRepository('AppBundle:Group')->find($groupId);
        $course = $group->getCourse();
        $chapters = $course->getChapters();
        $num = 0;
        foreach ($chapters as $chapter){
           $lessons =  $chapter->getLessons();
           $num = $num + count($lessons);
        }
        return $num;

    }


    public function stateFilter($startTime,$endTime)
    {
      $now   = strtotime(date("Y-m-d H:i:s"));
      $start = strtotime($startTime);
      $end   = strtotime($endTime);
      if($start > $now){
          return '未开始';
      }else if($end < $now){
          return '已结束';
      }else{
          return '正在进行中';
      }
    }
    public function singlescoreRateFilter($scoreRate,$questions)
    {
        $count = 0;
        foreach ($questions as $val){
            if($val->getQuestionType() === 0){
                $count++;
            }
        }
        if($count){
            $score = $scoreRate/$count;
            return '单选题 ( 每题'.$score.'分,共'.$count.'题,共'.$scoreRate.'分 )';
        }else{
            return '';
        }

    }
    public function multiplescoreRateFilter($scoreRate,$questions)
    {
        $count = 0;
        foreach ($questions as $val){
            if($val->getQuestionType() === 1){
                $count++;
            }
        }
        if($count){
            $score = $scoreRate/$count;
            return '多选题 ( 每题'.$score.'分,共'.$count.'题,共'.$scoreRate.'分 )';
        }else{
            return '';
        }

    }
    public function toLetterFilter($index)
    {

        return chr(ord($index) + 16);

    }


}