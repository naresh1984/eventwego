class AddstatusToEvent < ActiveRecord::Migration
  def up  
  add_column :events, :status, :integer ,:default => "0"
  end

  def down
  remove_column :events, :status, :integer
  end
end
