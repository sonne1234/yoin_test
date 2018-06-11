<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Condo\CondoBuilding;
use App\Domain\Resident\Resident;
use App\Domain\Resident\ResidentRepository;
use App\Domain\Unit\Unit;
use App\Domain\User\UserIdentity;

class DoctrineResidentRepository extends AbstractDoctrineRepository implements ResidentRepository
{
    protected function repositoryClassName(): string
    {
        return Resident::class;
    }

    public function getCondoFreeUnitsCount(string $condoId): int
    {
        $role = UserIdentity::ROLE_RESIDENT;
        $table = UserIdentity::TABLE_NAME;
        $unitTable = Unit::TABLE_NAME;

        return (int) $this
            ->em
            ->getConnection()
            ->executeQuery("
                  select count(*) from
                   (
                      select u.id as unit_id, count(ui.id)  as allcount 
                      from $unitTable u 
                      left join $table ui on u.id=ui.unit_id 
                      where ((ui.role='$role') or ui.id is null) and condo_id=:condoId group by u.id
                  ) s1
                   join (
                        select u.id as unit_id, count(ui.id)  as deactivatedCount
                        from $unitTable u left join $table ui on u.id=ui.unit_id
                        where ((ui.role='$role' and ui.initializedAt is not null and ui.isactive=false) or ui.id is null)  and condo_id=:condoId group by u.id
                  ) s2
                   using(unit_id)
                    where (s1.allcount - s2.deactivatedCount) = 0
                ",
                ['condoId' => $condoId]
            )
            ->fetchColumn();
    }

    public function getResidentsCount(): int
    {
        return $this
            ->createQueryBuilder('r')
            ->select('count(r)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getActiveResidentsCount(): int
    {
        return $this
            ->createQueryBuilder('r')
            ->select('count(r)')
            ->where('r.isActive = true')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getCondoBuildingFreeUnitsCount(CondoBuilding $condoBuilding): int
    {
        $role = UserIdentity::ROLE_RESIDENT;
        $table = UserIdentity::TABLE_NAME;
        $unitTable = Unit::TABLE_NAME;

        return (int) $this
            ->em
            ->getConnection()
            ->executeQuery("
                  select count(*) from
                   (
                      select u.condobuilding_id, count(ui.id)  as allcount 
                      from $unitTable u 
                      left join $table ui on u.id=ui.unit_id 
                      where ((ui.role='$role') or ui.id is null) and u.condobuilding_id = :condoBuildingId group by u.id
                  ) s1
                   join (
                        select u.condobuilding_id, count(ui.id)  as deactivatedCount
                        from $unitTable u left join $table ui on u.id=ui.unit_id
                        where ((ui.role='$role' and ui.initializedAt is not null and ui.isactive=false) or ui.id is null)  and u.condobuilding_id = :condoBuildingId group by u.id
                  ) s2
                   using(condobuilding_id)
                    where (s1.allcount - s2.deactivatedCount) = 0
                ",
                ['condoBuildingId' => $condoBuilding->getId()]
            )
            ->fetchColumn();
    }
}
