class AddbigintUidToUser < ActiveRecord::Migration
  def up
 change_column :users, :uid, :decimal
  end

  def down
  end
end
