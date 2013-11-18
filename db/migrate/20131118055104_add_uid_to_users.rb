class AddUidToUsers < ActiveRecord::Migration
  def change
  add_column :users, :uid, :integer ,:default => "0"
  add_column :users, :provider, :string 
  end
end
