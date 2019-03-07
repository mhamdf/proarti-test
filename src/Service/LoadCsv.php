<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DonationRepository;
use App\Repository\ProjectRepository;
use App\Repository\WinnerRepository;
use App\Repository\RewardRepository;
use App\Repository\UserRepository;
use League\Csv\CharsetConverter;
use App\Entity\Donation;
use App\Entity\Project;
use App\Entity\Reward;
use App\Entity\Winner;
use League\Csv\Reader;
use App\Entity\User;

class LoadCsv
{

  private $entityManager;
  private $projectRepo;
  private $rewardRepo;
  private $existproject;
  private $existreward;

  public function __construct(EntityManagerInterface $em, ProjectRepository $projectRepo, RewardRepository $rewardRepo)
  {
    // parent::__construct();

    $this->entityManager = $em;
    $this->projectRepo = $projectRepo;
    $this->rewardRepo = $rewardRepo;
  }

  public function load(?string $path)
  {
      $csv = Reader::createFromPath($path,"r");
      //let's set the output BOM
      $csv->setOutputBOM(Reader::BOM_UTF8);
      //let's convert the incoming data from iso-88959-15 to utf-8
      $csv->addStreamFilter('convert.iconv.ISO-8859-15/UTF-8');
      $csv->setDelimiter(';');
      $csv->setHeaderOffset(0);
      $records = $csv->getRecords();
      foreach ($records as $offset => $record) {
        $user = new User();
        $project = new Project();
        $donation = new Donation();
        $winner = new Winner();
        $reward_entity = new Reward();

        $first_name = $record['first_name'];
        $last_name = $record['last_name'];
        $project_name = $record['project_name'];
        $amount = (int)$record['amount'];
        $reward = $record['reward'];
        $reward_quantity = (int)$record['reward_quantity'];

        if ($first_name && $last_name) {
          $user->setFirstName($first_name);
          $user->setLastName($last_name);
        }
        if ($user) {
            if (null !== $this->existproject = $this->projectRepo->findOneBy(['project_name'=>$project_name])) {
                $user->addProject($this->existproject);
                $this->existproject->addUser($user);
              }
            else {
              $project->setProjectName($project_name);
              $project->addUser($user);
              $user->addProject($project);
              $this->entityManager->persist($project);
            }
        }
        if ($amount && $user) {
            $donation->setAmount($amount);
            $user->setDonation($donation);
        }
        if ($reward != null && $user) {
          // if (null !== $this->existreward = $this->rewardRepo->findOneBy(['reward' => $reward])) {
          //   $winner->setReward($this->existreward);
          //   $winner->setUser($user);
          //   $winner->setRewardQuantity($reward_quantity);
          //   $reward_entity->addWinner($winner);
          //   $user->setWinner($winner);
          // }
          // else {
            $reward_entity->setReward($reward);
            $winner->setReward($reward_entity);
            $winner->setUser($user);
            $winner->setRewardQuantity($reward_quantity);
            $reward_entity->addWinner($winner);
            $user->setWinner($winner);
          // }
        }

        $this->entityManager->persist($user);
        if($project->getProjectName()){
          $this->entityManager->persist($project);
          }
        $this->entityManager->persist($winner);
        $this->entityManager->persist($donation);
        $this->entityManager->persist($reward_entity);
        $this->entityManager->flush();
      }
      return count($records);
  }

  /*
   *
   *
   */
  // private function createData($record)
  // {
  //   $fields = [];
  //
  //   $user = new User();
  //   $project = new Project();
  //   $winner = new Winner();
  //   $donation = new Donation();
  //   $reward = new Reward();
  //
  //   if ($record['first_name'] && $record['first_name']) {
  //     $user->setFirstName($record['first_name']);
  //     $user->setLastName($record['last_name']);
  //     $fields[]=$user;
  //   }
  //
  //   if ($record['project_name']) {
  //     if ($existproject = $this->projects->findOneBy(['project_name'=>$record['project_name']])) {
  //       if ($user) {
  //
  //       }
  //     }
  //     else {
  //
  //     }
  //     $donation->setamount($record['first_name']);
  //     if ($user) {
  //       $user->setDonation($donation);
  //     }
  //     $fields[]=$donation;
  //   }
  //
  //
  //   if ($record['amount']) {
  //     $donation->setamount($record['first_name']);
  //     if ($user) {
  //       $user->setDonation($donation);
  //     }
  //     $fields[]=$donation;
  //   }
  //
  // }
  //
  // private function validateCsvData($username, $plainPassword, $email, $fullName)
  // {
  //     // first check if a user with the same username already exists.
  //     $existingProject = $this->projects->findOneBy(['username' => $username]);
  //     $existingUser = $this->projects->findOneBy(['first_name' => $first_name]);
  //
  //     if (null !== $existingUser) {
  //         throw new RuntimeException(sprintf('There is already a user registered with the "%s" username.', $username));
  //     }
  // }
}
